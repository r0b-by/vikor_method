<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PendingProfileUpdate; // Import model baru
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth Facade
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RegistrationApprovedNotification;
use App\Notifications\RegistrationRejectedNotification;
use App\Notifications\ProfileUpdateSubmittedNotification; // Notifikasi baru untuk admin
use App\Notifications\ProfileUpdateApprovedNotification; // Notifikasi baru untuk user
use App\Notifications\ProfileUpdateRejectedNotification; // Notifikasi baru untuk user

class UserController extends Controller
{
    public function __construct()
    {
        // Hanya admin yang bisa mengakses fungsi manajemen user umum (index, destroy, pendingRegistrations, approve/reject registration)
        $this->middleware(['auth', 'role:admin'])->except(['edit', 'updateProfile']);

        // Guru dan siswa bisa mengakses 'edit' dan 'updateProfile' untuk profil mereka sendiri
        // Admin juga bisa mengakses 'edit' dan 'updateProfile' karena mereka 'admin' dan kondisi pertama sudah mencakup Auth::user()->hasRole('admin')
        $this->middleware(['auth'])->only(['edit', 'updateProfile']);
    }

    /**
     * Display a listing of the users (for admin).
     * Menampilkan daftar pengguna yang statusnya bukan 'pending' pendaftaran.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('status', '!=', 'pending')->get();
        return view('dashboard.user-management', compact('users'));
    }

    /**
     * Show the form for editing the specified user (for admin, guru, and siswa).
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // Admin bisa mengedit user mana pun.
        // Guru dan siswa hanya bisa mengedit profil mereka sendiri.
        if (Auth::user()->hasRole('admin') || Auth::id() === $user->id) {
            $roles = Role::all();
            return view('users.edit', compact('user', 'roles'));
        }

        abort(403, 'Unauthorized action.'); // Akses ditolak jika tidak memenuhi kriteria
    }

    /**
     * Update the specified user in storage.
     * Memperbarui pengguna yang ditentukan di penyimpanan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // Validasi data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nis' => 'nullable|string|max:20|unique:users,nis,' . $user->id,
            'kelas' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive', // Hanya admin yang bisa mengubah status
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        // Jika user adalah admin, biarkan dia mengubah semua field termasuk peran dan status
        if (Auth::user()->hasRole('admin')) {
            $user->update($request->only('name', 'email', 'nis', 'kelas', 'jurusan', 'alamat', 'status'));

            if ($request->has('roles')) {
                $user->syncRoles($request->input('roles'));
            }

            return redirect()->route('user.management')->with('success', 'User updated successfully.');
        } else {
            // Jika bukan admin, hanya izinkan update data tertentu (selain status dan peran)
            $user->update($request->only('name', 'email', 'nis', 'kelas', 'jurusan', 'alamat'));
            return redirect()->route('users.edit', $user->id)->with('success', 'Profil Anda berhasil diperbarui.');
        }
    }


    /**
     * Handle profile update requests from non-admin users.
     * Menangani permintaan pembaruan profil dari pengguna non-admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nis' => 'nullable|string|max:20|unique:users,nis,' . $user->id,
            'kelas' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
        ]);

        // Filter hanya data yang berubah
        $originalData = $user->only(['name', 'email', 'nis', 'kelas', 'jurusan', 'alamat']);
        $proposedData = array_filter($validatedData, function ($value, $key) use ($originalData) {
            return $value !== $originalData[$key];
        }, ARRAY_FILTER_USE_BOTH);


        if (empty($proposedData)) {
            return back()->with('info', 'Tidak ada perubahan yang diajukan.');
        }

        // Buat atau perbarui entri PendingProfileUpdate
        $pendingUpdate = PendingProfileUpdate::updateOrCreate(
            ['user_id' => $user->id, 'status' => 'pending'],
            [
                'original_data' => $originalData,
                'proposed_data' => $proposedData,
                'reason' => $request->input('reason', 'Perubahan profil oleh pengguna.'),
                'status' => 'pending',
            ]
        );

        // Kirim notifikasi ke admin
        $adminUsers = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        Notification::send($adminUsers, new ProfileUpdateSubmittedNotification($user, $pendingUpdate));

        return back()->with('success', 'Perubahan profil Anda telah diajukan dan menunggu persetujuan administrator.');
    }

    /**
     * Display a listing of pending user registrations (for admin).
     * Menampilkan daftar pengguna yang status pendaftarannya 'pending'.
     *
     * @return \Illuminate\Http\Response
     */
   public function pendingRegistrations()
    {
        $pendingUsers = User::where('status', 'pending')->get();
        return view('auth.registration-pending', compact('pendingUsers'));
    }

    /**
     * Approve a pending user registration.
     * Menyetujui pendaftaran pengguna yang tertunda.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function approveRegistration(Request $request, User $user)
    {
        if ($user->status === 'pending') {
            $user->status = 'active';
            $user->approved_by = Auth::id(); // Menggunakan Auth::id() untuk user yang sedang login
            $user->approved_at = now();
            $user->save();

            // Kirim notifikasi persetujuan ke pengguna
            Notification::send($user, new RegistrationApprovedNotification($user));

            return redirect()->route('admin.pending-registrations')->with('success', 'Pendaftaran pengguna berhasil disetujui.');
        }

        return redirect()->route('admin.pending-registrations')->with('error', 'Pendaftaran tidak dalam status menunggu konfirmasi.');
    }

    /**
     * Reject a pending user registration.
     * Menolak pendaftaran pengguna yang tertunda.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function rejectRegistration(Request $request, User $user)
    {
        if ($user->status === 'pending') {
            $user->status = 'rejected'; // Ubah status menjadi 'rejected'
            $user->approved_by = Auth::id(); // Menggunakan Auth::id() untuk user yang sedang login
            $user->approved_at = now();
            $user->save();

            // Kirim notifikasi penolakan ke pengguna
            Notification::send($user, new RegistrationRejectedNotification($user));

            return redirect()->route('admin.pending-registrations')->with('success', 'Pendaftaran pengguna berhasil ditolak.');
        }

        return redirect()->route('admin.pending-registrations')->with('error', 'Pendaftaran tidak dalam status menunggu konfirmasi.');
    }

    /**
     * Display a listing of pending profile updates (for admin).
     * Menampilkan daftar pembaruan profil yang tertunda.
     *
     * @return \Illuminate\Http\Response
     */
    public function pendingProfileUpdates()
    {
        $pendingUpdates = PendingProfileUpdate::with('user', 'approver')
                                        ->where('status', 'pending')
                                        ->orderBy('created_at', 'desc')
                                        ->get();
        return view('admin.pending-profile-updates', compact('pendingUpdates'));
    }

    /**
     * Approve a pending profile update.
     * Menyetujui pembaruan profil yang tertunda.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PendingProfileUpdate  $pendingUpdate
     * @return \Illuminate\Http\Response
     */
    public function approveProfileUpdate(Request $request, PendingProfileUpdate $pendingUpdate)
    {
        if ($pendingUpdate->status === 'pending') {
            // Perbarui data pengguna dengan data yang diajukan
            $user = $pendingUpdate->user;
            $user->fill($pendingUpdate->proposed_data);
            $user->save();

            // Ubah status pending update menjadi 'approved'
            $pendingUpdate->status = 'approved';
            $pendingUpdate->approved_by = Auth::id(); // Menggunakan Auth::id() untuk user yang sedang login
            $pendingUpdate->approved_at = now();
            $pendingUpdate->save();

            // Kirim notifikasi ke pengguna bahwa perubahan profil mereka telah disetujui
            Notification::send($user, new ProfileUpdateApprovedNotification($user));

            return redirect()->route('admin.pending-profile-updates')->with('success', 'Perubahan profil berhasil disetujui.');
        }

        return redirect()->route('admin.pending-profile-updates')->with('error', 'Pembaruan profil tidak dalam status menunggu konfirmasi.');
    }

    /**
     * Reject a pending profile update.
     * Menolak pembaruan profil yang tertunda.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PendingProfileUpdate  $pendingUpdate
     * @return \Illuminate\Http\Response
     */
    public function rejectProfileUpdate(Request $request, PendingProfileUpdate $pendingUpdate)
    {
        if ($pendingUpdate->status === 'pending') {
            // Ubah status pending update menjadi 'rejected'
            $pendingUpdate->status = 'rejected';
            $pendingUpdate->approved_by = Auth::id(); // Menggunakan Auth::id() untuk user yang sedang login
            $pendingUpdate->approved_at = now();
            $pendingUpdate->save();

            // Kirim notifikasi ke pengguna bahwa perubahan profil mereka ditolak
            Notification::send($pendingUpdate->user, new ProfileUpdateRejectedNotification($pendingUpdate->user));

            return redirect()->route('admin.pending-profile-updates')->with('success', 'Perubahan profil berhasil ditolak.');
        }

        return redirect()->route('admin.pending-profile-updates')->with('error', 'Pembaruan profil tidak dalam status menunggu konfirmasi.');
    }

    /**
     * Remove the specified user from storage.
     * Menghapus pengguna dari penyimpanan.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.management')->with('success', 'User deleted successfully.');
    }
}