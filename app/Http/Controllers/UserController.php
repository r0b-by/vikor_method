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

        // Middleware 'role:admin' diterapkan HANYA untuk metode yang ditujukan
        // untuk admin mengelola pengguna lain atau melakukan aksi admin-spesifik.
        $this->middleware('role:admin')->only([
            'index',
            'update', // Ini untuk admin memperbarui pengguna lain
            'destroy', // Ini untuk admin menghapus pengguna lain
            'pendingRegistrations',
            'approveRegistration',
            'rejectRegistration',
            'pendingProfileUpdates',
            'approveProfileUpdate',
            'rejectProfileUpdate',
        ]);
        
        // Metode 'editProfile' dan 'updateProfile' tidak masuk dalam 'role:admin'
        // karena itu adalah untuk semua pengguna yang diautentikasi mengelola profil mereka sendiri.
    }

    /**
     * Display a listing of the users (for admin).
     * Menampilkan daftar pengguna yang statusnya bukan 'pending' pendaftaran.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Factory|View
    {

        $users = User::with('roles')
           ->whereIn('status', ['active', 'rejected'])
           ->paginate(10); // 10 items per page
        return view('dashboard.user-management', compact('users'));
    }

    /**
     * Display the profile of a specific user.
     * Menampilkan profil pengguna berdasarkan ID.
     *
     * @param   \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user): View|Factory
    {
        // Baris ini akan memeriksa kebijakan 'view' pada UserPolicy.
        // Izin 'view' di UserPolicy Anda masih mengandalkan 'before' untuk admin,
        // dan 'authenticatedUser->id === userToView->id' untuk non-admin melihat dirinya sendiri.
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

    /**
     * Display the profile of the currently authenticated user.
     * Menampilkan profil pengguna yang sedang login.
     *
     * @return \Illuminate\Http\Response
     */
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
     * Show the form for editing the specified user (for admin to edit other users).
     *
     * @param   \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // Ini adalah metode 'edit' untuk admin mengedit pengguna lain.
        // Otorisasi melalui UserPolicy@update. Admin diizinkan oleh metode 'before' di Policy.
        // Namun, jika Anda menggunakan permission spesifik untuk edit user lain, gunakan itu.
        // Contoh: $this->authorize('user-edit', $user); // Memerlukan izin 'user-edit'
        $this->authorize('update', $user); // Policy `update` akan memanggil `before` untuk admin, atau cek self-edit untuk non-admin.
        $roles = Role::all(); // Admin mungkin perlu melihat dan mengubah peran
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user by an admin.
     * Metode ini untuk admin memperbarui data pengguna lain, termasuk status dan peran.
     *
     * @param   \Illuminate\Http\Request  $request
     * @param   \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // Pastikan $user di sini adalah user dari route model binding (user yang akan diupdate),
        // bukan user yang sedang login.
        // GANTI INI: Gunakan izin granular yang Anda miliki!
        $this->authorize('user-edit'); // Otorisasi admin untuk edit user lain.
                                       // UserPolicy@before akan mengizinkan jika admin,
                                       // atau Gate 'user-edit' yang mengizinkan.

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'nis' => 'nullable|string|max:20|unique:users,nis,'.$user->id,
            'kelas' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'status' => 'required|in:pending,active,inactive,rejected', // Hanya admin yang dapat mengubah status ini
            'roles' => 'required|array', // Hanya admin yang dapat mengubah peran
        ]);

        $user->update($validated);
        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.edit', $user->id)
            ->with('success', 'User updated successfully');
    }

    /**
     * Show the form for editing the currently authenticated user's profile.
     * Menampilkan form untuk pengguna yang sedang login mengedit profil mereka sendiri.
     *
     * @return \Illuminate\Http\Response
     */
    public function editProfile(): Factory|View
    {
        $user = auth()->user(); // Get the currently authenticated user
        
        // Pastikan pengguna ada
        if (!$user) {
            abort(404, 'User not found');
        }
        
        // Tidak perlu $this->authorize('update', $user); di sini, karena form ini hanya untuk user itu sendiri
        // dan otorisasi akan dilakukan di updateProfile method.
        // Anda mungkin ingin membuat view terpisah untuk edit profil pengguna biasa agar tidak
        // menampilkan opsi pengubahan role/status.
        return view('users.edit', compact('user')); // Pastikan ada view users.edit-profile
    }

    /**
     * Update the currently authenticated user's profile.
     * Menangani pengiriman form dari editProfile() untuk pembaruan profil oleh pengguna itu sendiri.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user(); // Ini adalah user yang sedang login
        
        // Otorisasi: Pastikan user yang sedang login diizinkan mengupdate profil ini.
        // UserPolicy@update akan mengizinkan jika $user->id === $userToUpdate->id
        $this->authorize('update', $user); 

        // Validasi yang lebih terbatas untuk pembaruan profil oleh pengguna itu sendiri
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'nis' => 'nullable|string|max:20|unique:users,nis,'.$user->id,
            'kelas' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            // 'status' dan 'roles' TIDAK BOLEH ada di sini, hanya admin yang bisa mengubahnya.
        ]);

        $user->update($validated);

        // Jika Anda ingin notifikasi tentang perubahan profil, bisa tambahkan di sini
        // Notification::send(User::role('admin')->get(), new ProfileUpdateSubmittedNotification($user, $validated));

        return redirect()->route('profile.edit') // Arahkan kembali ke halaman edit profil
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Display a listing of pending user registrations (for admin).
     * Menampilkan daftar pengguna yang status pendaftarannya 'pending'.
     *
     * @return \Illuminate\Http\Response
     */
    public function pendingRegistrations(): Factory|View
    {
        // Metode ini sudah dilindungi oleh middleware 'role:admin' di constructor.
        // Karena ada izin 'approval-list', Anda bisa menambahkan $this->authorize('approval-list'); jika mau.
        $pendingUsers = User::role('siswa')->where('status', 'pending')->get();
        return view('admin.pending-users', compact('pendingUsers'));
    }

    /**
     * Approve a pending user registration.
     * Menyetujui pendaftaran pengguna yang tertunda.
     *
     * @param   int   $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveRegistration($userId): RedirectResponse
    {
        // GANTI INI: Gunakan izin granular yang Anda miliki!
        $this->authorize('approval-approve');

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
                        'tahun_ajaran' => $user->tahun_ajaran ?? null, // Menambahkan tahun_ajaran dari user
                        'semester' => $user->semester ?? null,     // Menambahkan semester dari user
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectRegistration($userId): RedirectResponse
    {
        // GANTI INI: Gunakan izin granular yang Anda miliki!
        $this->authorize('approval-reject');

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
        // Otorisasi eksplisit, meskipun ada middleware di constructor
        // GANTI INI: Gunakan izin granular yang Anda miliki!
        $this->authorize('approval-list'); // Jika ada izin khusus untuk melihat daftar approval
        
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveProfileUpdate(Request $request, PendingProfileUpdate $pendingUpdate): RedirectResponse
    {
        // Otorisasi eksplisit
        // GANTI INI: Gunakan izin granular yang Anda miliki!
        $this->authorize('approval-approve'); 

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectProfileUpdate(Request $request, PendingProfileUpdate $pendingUpdate): RedirectResponse
    {
        // Otorisasi eksplisit
        // GANTI INI: Gunakan izin granular yang Anda miliki!
        $this->authorize('approval-reject'); 

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
        // GANTI INI: Gunakan izin granular yang Anda miliki!
        // Anda memiliki izin 'user-delete' yang lebih spesifik.
        $this->authorize('user-delete'); 
        
        // Pencegahan agar pengguna yang sedang login tidak menghapus akunnya sendiri
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('admin.user.management')->with('success', 'User deleted successfully.');
    }
}