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
{    public function __construct()
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user): View|Factory
    {
        $this->authorize('view', $user); // Requires admin role
        
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
        $this->authorize('update', $user); // Add authorization
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }


    /**
    * Show the form for editing the specified user.
    *
    * @param  \App\Models\User  $user
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
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

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }


    /**
     * Update the profile of the currently authenticated user.
     * Menangani permintaan pembaruan profil dari pengguna non-admin (atau admin untuk profilnya sendiri).
     *
     * @param   \Illuminate\Http\Request  $request
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
        if ($pendingUpdate->status === 'pending') {

            // Mendapatkan instance User terkait dari pending update
            // Laravel secara otomatis me-load User di sini karena ini adalah relasi Eloquent
            $user = $pendingUpdate->user;

            // Karena 'proposed_data' sudah di-cast ke 'array' di model PendingProfileUpdate,
            // Anda tidak perlu lagi memanggil json_decode().
            // Variabel $proposedData sudah akan berupa array.
            $proposedData = $pendingUpdate->proposed_data; // <--- INI PERBAIKANNYA

            // Mengisi data profil user dengan data yang diusulkan
            $user->fill($proposedData);
            $user->save();

            // Ubah status pending update menjadi 'approved'
            $pendingUpdate->status = 'approved';
            $pendingUpdate->approved_by = Auth::id();
            $pendingUpdate->approved_at = now();
            $pendingUpdate->save();

            // Kirim notifikasi ke user
            // Pastikan konstruktor ProfileUpdateApprovedNotification($user) menerima instance App\Models\User
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
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('manage users'); 
        $user->delete();
        return redirect()->route('user.management')->with('success', 'User deleted successfully.');
    }
}
