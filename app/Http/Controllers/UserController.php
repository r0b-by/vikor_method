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
        return view('users.index', compact('users'));
    }

    /**
     * Display a listing of pending user registrations for admin approval.
     * Menampilkan daftar pendaftaran pengguna yang menunggu persetujuan admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function pendingRegistrations()
    {
        $pendingUsers = User::where('status', 'pending')->get();
        return view('admin.pending-users', compact('pendingUsers'));
    }

    /**
     * Display a listing of pending profile updates for admin approval.
     * Menampilkan daftar perubahan profil yang menunggu persetujuan admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function pendingProfileUpdates()
    {
        $pendingUpdates = PendingProfileUpdate::with('user')->where('status', 'pending')->get();
        return view('admin.pending-profile-updates', compact('pendingUpdates'));
    }

    /**
     * Show the form for editing the specified user's profile.
     * Memungkinkan user untuk mengedit profilnya sendiri, atau admin mengedit profil user lain.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // Memastikan user hanya bisa mengedit profilnya sendiri kecuali admin
        if (Auth::user()->hasRole('admin') || Auth::user()->id === $user->id) {
            // Cek apakah ada pembaruan profil yang tertunda untuk user ini
            $pendingUpdate = PendingProfileUpdate::where('user_id', $user->id)
                                                ->where('status', 'pending')
                                                ->first();
            $roles = Role::all(); // Untuk admin yang mungkin mengelola peran

            return view('users.edit', compact('user', 'roles', 'pendingUpdate'));
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * Handle the submission of a user's profile update request.
     * Ini akan menyimpan ke pending_profile_updates, bukan langsung ke tabel users.
     * Digunakan oleh guru/siswa untuk mengajukan perubahan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request, User $user)
    {
        // Memastikan user hanya bisa mengedit profilnya sendiri kecuali admin
        if (!Auth::user()->hasRole('admin') && Auth::user()->id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi data yang diajukan
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'reason' => 'nullable|string|max:500', // Alasan perubahan
        ]);

        // Ambil data asli dari user
        $originalData = $user->only(['name', 'email']); // Sesuaikan kolom yang relevan

        // Periksa apakah ada perubahan yang benar-benar diajukan
        $hasChanges = false;
        if ($originalData['name'] !== $validatedData['name'] || $originalData['email'] !== $validatedData['email']) {
            $hasChanges = true;
        }

        if (!$hasChanges) {
            return redirect()->back()->with('info', 'Tidak ada perubahan yang diajukan.');
        }

        // Hapus permintaan tertunda sebelumnya jika ada untuk user ini
        PendingProfileUpdate::where('user_id', $user->id)->where('status', 'pending')->delete();

        // Simpan permintaan perubahan ke tabel pending_profile_updates
        $pendingUpdate = PendingProfileUpdate::create([
            'user_id' => $user->id,
            'original_data' => $originalData,
            'proposed_data' => [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
            ],
            'reason' => $validatedData['reason'],
            'status' => 'pending',
        ]);

        // Kirim notifikasi ke semua admin
        $admins = User::role('admin')->get();
        Notification::send($admins, new ProfileUpdateSubmittedNotification($user, $pendingUpdate));

        return redirect()->back()->with('success', 'Perubahan profil Anda telah diajukan dan sedang menunggu konfirmasi admin.');
    }

    /**
     * Approve a pending user registration.
     * Menyetujui pendaftaran pengguna yang tertunda.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function approveRegistration(User $user)
    {
        if ($user->status === 'pending') {
            $user->status = 'active';
            $user->save();

            // Kirim notifikasi ke pengguna bahwa pendaftaran mereka disetujui
            Notification::send($user, new RegistrationApprovedNotification($user));

            return redirect()->route('admin.pending-registrations')->with('success', 'Pendaftaran pengguna berhasil dikonfirmasi.');
        }

        return redirect()->route('admin.pending-registrations')->with('error', 'Pengguna tidak dalam status menunggu konfirmasi.');
    }

    /**
     * Reject a pending user registration.
     * Menolak pendaftaran pengguna yang tertunda.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function rejectRegistration(User $user)
    {
        if ($user->status === 'pending') {
            $user->status = 'rejected'; // Atau $user->delete(); jika ingin menghapus akun
            $user->save();

            // Kirim notifikasi ke pengguna bahwa pendaftaran mereka ditolak
            Notification::send($user, new RegistrationRejectedNotification($user));

            return redirect()->route('admin.pending-registrations')->with('success', 'Pendaftaran pengguna berhasil ditolak.');
        }

        return redirect()->route('admin.pending-registrations')->with('error', 'Pengguna tidak dalam status menunggu konfirmasi.');
    }

    /**
     * Approve a pending profile update.
     * Menyetujui perubahan profil yang tertunda.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PendingProfileUpdate  $pendingUpdate
     * @return \Illuminate\Http\Response
     */
    public function approveProfileUpdate(Request $request, PendingProfileUpdate $pendingUpdate)
    {
        if ($pendingUpdate->status === 'pending') {
            $user = $pendingUpdate->user;

            // Perbarui data pengguna dengan data yang diajukan
            $user->name = $pendingUpdate->proposed_data['name'] ?? $user->name;
            $user->email = $pendingUpdate->proposed_data['email'] ?? $user->email;
            // Anda bisa menambahkan kolom lain di sini jika ada di proposed_data
            $user->save();

            // Perbarui status pending update
            $pendingUpdate->status = 'approved';
            $pendingUpdate->approved_by = Auth::id(); // Menggunakan Auth::id() untuk user yang sedang login
            $pendingUpdate->approved_at = now();
            $pendingUpdate->save();

            // Kirim notifikasi ke pengguna bahwa perubahan profil mereka disetujui
            Notification::send($user, new ProfileUpdateApprovedNotification($user));

            return redirect()->route('admin.pending-profile-updates')->with('success', 'Perubahan profil berhasil dikonfirmasi.');
        }

        return redirect()->route('admin.pending-profile-updates')->with('error', 'Pembaruan profil tidak dalam status menunggu konfirmasi.');
    }

    /**
     * Reject a pending profile update.
     * Menolak perubahan profil yang tertunda.
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
