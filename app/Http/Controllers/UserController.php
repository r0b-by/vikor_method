<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alternatif;
use Illuminate\Support\Facades\DB;
use App\Models\PendingProfileUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RegistrationApprovedNotification;
use App\Notifications\RegistrationRejectedNotification;
use App\Notifications\ProfileUpdateSubmittedNotification;
use App\Notifications\ProfileUpdateApprovedNotification;
use App\Notifications\ProfileUpdateRejectedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('role:admin')->only([
            'index',
            'update',
            'destroy',
            'pendingRegistrations',
            'approveRegistration',
            'rejectRegistration',
            'pendingProfileUpdates',
            'approveProfileUpdate',
            'rejectProfileUpdate',
        ]);
    }

    /**
     * Display a listing of the users (for admin).
     * Menampilkan daftar pengguna yang statusnya bukan 'pending' pendaftaran.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Factory|View
    {
        // Hanya tampilkan user yang statusnya 'active' atau 'rejected'
        $users = User::whereIn('status', ['active', 'rejected'])->get();
        return view('dashboard.user-management', compact('users'));
    }

    /**
     * Display the profile of the currently authenticated user.
     * Menampilkan profil pengguna yang sedang login.
     *
     * @param   \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user): View|Factory
    {
        // Baris ini akan memeriksa kebijakan 'view' pada UserPolicy
        // Pastikan UserPolicy Anda mengizinkan admin untuk melihat pengguna lain,
        // dan pengguna untuk melihat profil mereka sendiri.
        $this->authorize('view', $user); 
        
        // For students, show with their alternatif data
        if ($user->hasRole('siswa')) {
            return view('siswa.profile.show', [
                'user' => $user,
                'alternatif' => $user->alternatif
            ]);
        }
        
        // For other users (admin/guru)
        return view('users.show', compact('user'));
    }

    public function showProfile(): View|Factory
    {
        $user = Auth::user();

        if ($user->hasRole('siswa')) {
            return view('siswa.profile.show', [
                'user' => $user,
                'alternatif' => $user->alternatif
            ]);
        }

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user (for admin, guru, and siswa).
     *
     * @param   \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // Hapus baris "$user = auth()->user();" di sini.
        // Variabel $user sudah di-inject dari route model binding.
        $this->authorize('update', $user); // Ini akan memanggil UserPolicy@update
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }


    /**
    * Update the specified user.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\User  $user
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, User $user)
    {
        // Hapus baris 'if ($authenticatedUser->hasRole('admin')) { return true; }'
        // Karena otorisasi sudah ditangani oleh $this->authorize('update', $user);
        // dan middleware 'role:admin' di constructor.
        $this->authorize('update', $user); // Ini akan memanggil UserPolicy@update
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'nis' => 'nullable|string|max:20|unique:users,nis,'.$user->id,
            'kelas' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'status' => 'required|in:pending,active,inactive,rejected',
            'roles' => 'required|array',
        ]);

        $user->update($validated);
        $user->syncRoles($request->roles);

         return redirect()->route('admin.users.edit', $user->id) // Pass the user's ID
        ->with('success', 'User updated successfully');
    }


    /**
     * Show the form for editing the currently authenticated user's profile.
     * Menangani permintaan pembaruan profil dari pengguna non-admin (atau admin untuk profilnya sendiri).
     *
     * @return \Illuminate\Http\Response
     */
    public function editProfile()
    {
        $user = auth()->user(); // Get the currently authenticated user
        $roles = Role::all(); // Only if you need roles for the form
        
        // Make sure the user exists
        if (!$user) {
            abort(404, 'User not found');
        }
        
        return view('users.edit', compact('user', 'roles'));
    }

    // CATATAN PENTING: Anda mungkin perlu menambahkan public function updateProfile(Request $request)
    // untuk menangani pengiriman form dari editProfile() jika ingin memisahkannya dari method update() admin.
    // Atau pastikan form editProfile() mengarah ke route update() yang benar dengan otorisasi yang sesuai.

    /**
     * Display a listing of pending user registrations (for admin).
     * Menampilkan daftar pengguna yang status pendaftarannya 'pending'.
     *
     * @return \Illuminate\Http\Response
     */
    public function pendingRegistrations(): Factory|View
    {
       $pendingUsers = User::role('siswa')->where('status', 'pending')->get();
       return view('admin.pending-users', compact('pendingUsers'));
    }

    /**
     * Approve a pending user registration.
     * Menyetujui pendaftaran pengguna yang tertunda.
     *
     * @param   int   $userId
     */
    public function approveRegistration($userId): RedirectResponse
    {
        $this->authorize('approve registrations');

        $user = User::find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        if ($user->status === 'pending' && $user->hasRole('siswa')) {
            DB::beginTransaction();
            try {
                $user->status = 'active'; // Ubah status menjadi 'active'
                $user->approved_by = Auth::id(); // Menggunakan Auth::id() untuk user yang sedang login
                $user->approved_at = now();
                $user->save();

                if (!Alternatif::where('user_id', $user->id)->exists()) {
                    Alternatif::create([
                        'user_id' => $user->id,
                        'alternatif_code' => 'ALT-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                        'alternatif_name' => $user->name,
                        'tahun_ajaran' => $user->tahun_ajaran, // Menambahkan tahun_ajaran dari user
                        'semester' => $user->semester,       // Menambahkan semester dari user
                        'status_perhitungan' => 'pending',
                    ]);
                }

                DB::commit();
                Notification::send($user, new RegistrationApprovedNotification($user));

                return redirect()->back()->with('success', 'Siswa berhasil disetujui dan alternatif telah dibuat.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Terjadi kesalahan saat menyetujui siswa: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('info', 'Pengguna ini sudah disetujui atau bukan siswa pending.');
    }

    /**
     * Reject a pending user registration.
     * Menolak pendaftaran pengguna yang tertunda.
     *
     * @param   int   $userId
     * @return \Illuminate\Http\Response
     */
    public function rejectRegistration($userId): RedirectResponse
    {
        $this->authorize('reject registrations');

        $user = User::find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        if ($user->status === 'pending' && $user->hasRole('siswa')) {
            $user->status = 'rejected';
            $user->approved_by = Auth::id();
            $user->approved_at = now();
            $user->save();

            Notification::send($user, new RegistrationRejectedNotification($user));

            return redirect()->back()->with('success', 'Pendaftaran siswa berhasil ditolak.');
        }

        return redirect()->back()->with('info', 'Pengguna ini tidak dalam status pending pendaftaran.');
    }

    /**
     * Display a listing of pending profile updates (for admin).
     * Menampilkan daftar pembaruan profil yang tertunda.
     *
     * @return \Illuminate\Http\Response
     */
    public function pendingProfileUpdates(): Factory|View
    {
        $this->authorize('approve registrations');
        $pendingUpdates = PendingProfileUpdate::where('status', 'pending')->with('user', 'approver')
                                             ->orderBy('created_at', 'desc')
                                             ->get();
        return view('admin.pending-profile-updates', compact('pendingUpdates'));
    }

    /**
     * Approve a pending profile update.
     * Menyetujui pembaruan profil yang tertunda.
     *
     * @param   \Illuminate\Http\Request  $request
     * @param   \App\Models\PendingProfileUpdate  $pendingUpdate
     * @return \Illuminate\Http\Response
     */
    public function approveProfileUpdate(Request $request, PendingProfileUpdate $pendingUpdate): RedirectResponse
    {
        // Metode ini dilindungi oleh middleware 'role:admin' di constructor.
        // Tidak perlu $this->authorize() tambahan di sini kecuali Anda ingin pengecekan spesifik lainnya.
        if ($pendingUpdate->status === 'pending') {

            $user = $pendingUpdate->user;

            $proposedData = $pendingUpdate->proposed_data;

            $user->fill($proposedData);
            $user->save();

            $pendingUpdate->status = 'approved';
            $pendingUpdate->approved_by = Auth::id();
            $pendingUpdate->approved_at = now();
            $pendingUpdate->save();

            Notification::send($user, new ProfileUpdateApprovedNotification($user));

            return redirect()->route('admin.users.pending-profile-updates')->with('success', 'Perubahan profil berhasil disetujui.');
        }

        return redirect()->route('admin.users.pending-profile-updates')->with('error', 'Pembaruan profil tidak dalam status menunggu konfirmasi.');
    }


    /**
     * Reject a pending profile update.
     * Menolak pembaruan profil yang tertunda.
     *
     * @param   \Illuminate\Http\Request  $request
     * @param   \App\Models\PendingProfileUpdate  $pendingUpdate
     * @return \Illuminate\Http\Response
     */
    public function rejectProfileUpdate(Request $request, PendingProfileUpdate $pendingUpdate): RedirectResponse
    {
        // Metode ini dilindungi oleh middleware 'role:admin' di constructor.
        if ($pendingUpdate->status === 'pending') {
            $pendingUpdate->status = 'rejected';
            $pendingUpdate->approved_by = Auth::id();
            $pendingUpdate->approved_at = now();
            $pendingUpdate->save();

            Notification::send($pendingUpdate->user, new ProfileUpdateRejectedNotification($pendingUpdate->user));

            return redirect()->route('admin.users.pending-profile-updates')->with('success', 'Perubahan profil berhasil ditolak.');
        }

        return redirect()->route('admin.users.pending-profile-updates')->with('error', 'Pembaruan profil tidak dalam status menunggu konfirmasi.');
    }

    /**
     * Remove the specified user from storage.
     * Menghapus pengguna dari penyimpanan.
     *
     * @param   \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        // Hapus baris 'if ($authenticatedUser->hasRole('admin')) { return true; }'
        // Karena otorisasi sudah ditangani oleh $this->authorize('manage users');
        // dan middleware 'role:admin' di constructor.
        $this->authorize('manage users'); 
        
        // Pencegahan agar admin tidak menghapus akunnya sendiri
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('user.management')->with('success', 'User deleted successfully.');
    }
}