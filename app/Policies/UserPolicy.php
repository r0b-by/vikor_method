<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     * This method runs BEFORE any other policy methods (view, update, create, delete, etc.).
     * It's ideal for granting super-admin privileges.
     *
     * @param  \App\Models\User  $user The authenticated user.
     * @param  string  $ability The name of the ability being checked (e.g., 'update', 'view').
     * @return \Illuminate\Auth\Access\Response|bool|null
     */
    public function before(User $user, string $ability): ?bool
    {
        // Jika pengguna yang terautentikasi memiliki peran 'admin',
        // mereka secara otomatis diizinkan untuk melakukan SEMUA kemampuan.
        // Metode policy spesifik di bawah tidak akan dipanggil untuk admin
        // kecuali metode 'before' ini mengembalikan `null`.
        if ($user->hasRole('admin')) {
            return true; // Izinkan akses penuh untuk admin
        }

        // Jika pengguna BUKAN admin, kembalikan null untuk memungkinkan
        // metode policy spesifik (misalnya, 'update', 'view') untuk menangani otorisasi.
        return null;
    }

    /**
     * Determine whether the user can view any models (e.g., see a list of all users).
     * Metode ini akan dipanggil HANYA jika 'before' mengembalikan null (yaitu, untuk non-admin).
     *
     * @param  \App\Models\User  $authenticatedUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $authenticatedUser): bool
    {
        // Non-admin secara umum tidak diizinkan melihat daftar semua pengguna.
        // Jika ada peran lain yang diizinkan, tambahkan pengecekan permission di sini.
        // Contoh: return $authenticatedUser->hasPermissionTo('view users list');
        return false;
    }

    /**
     * Determine whether the user can view the model (e.g., view a specific user's profile).
     * Metode ini akan dipanggil HANYA jika 'before' mengembalikan null.
     *
     * @param  \App\Models\User  $authenticatedUser
     * @param  \App\Models\User  $userToView
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $authenticatedUser, User $userToView): bool
    {
        // Pengguna non-admin hanya dapat melihat profil mereka sendiri.
        return $authenticatedUser->id === $userToView->id;
        // Admin sudah ditangani oleh metode 'before'.
    }

    /**
     * Determine whether the user can create new users.
     * Metode ini akan dipanggil HANYA jika 'before' mengembalikan null.
     * (Biasanya hanya admin yang dapat membuat pengguna baru, atau melalui proses pendaftaran.)
     *
     * @param  \App\Models\User  $authenticatedUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $authenticatedUser): bool
    {
        // Non-admin secara default tidak diizinkan untuk membuat pengguna baru.
        // Jika ada permission spesifik, cek di sini:
        // return $authenticatedUser->hasPermissionTo('create users');
        return false;
    }

    /**
     * Determine whether the user can update the model.
     * Metode ini akan dipanggil HANYA jika 'before' mengembalikan null.
     *
     * @param  \App\Models\User  $authenticatedUser
     * @param  \App\Models\User  $userToUpdate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $authenticatedUser, User $userToUpdate): bool
    {
        // Pengguna non-admin hanya dapat memperbarui profil mereka sendiri.
        return $authenticatedUser->id === $userToUpdate->id;
        // Admin sudah ditangani oleh metode 'before'.
    }

    /**
     * Determine whether the user can delete the model.
     * Metode ini akan dipanggil HANYA jika 'before' mengembalikan null.
     *
     * @param  \App\Models\User  $authenticatedUser
     * @param  \App\Models\User  $userToDelete
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $authenticatedUser, User $userToDelete): bool
    {
        // Pencegahan agar pengguna (termasuk admin yang lolos dari `before` jika logika diubah)
        // tidak menghapus akunnya sendiri. Ini merupakan DENIAL yang kuat.
        if ($authenticatedUser->id === $userToDelete->id) {
            return Response::deny('Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Untuk pengguna non-admin: Mereka tidak diizinkan menghapus pengguna lain.
        // Admin sudah diizinkan menghapus pengguna lain oleh metode 'before'.
        return false;
    }

    /**
     * Determine whether the user can approve registrations.
     * Metode ini akan dipanggil HANYA jika 'before' mengembalikan null.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function approveRegistrations(User $user): bool
    {
        // Admin sudah diizinkan oleh metode 'before'.
        // Untuk pengguna non-admin, cek apakah mereka memiliki permission spesifik.
        return $user->hasPermissionTo('approve registrations');
    }

    /**
     * Determine whether the user can reject registrations.
     * Metode ini akan dipanggil HANYA jika 'before' mengembalikan null.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function rejectRegistrations(User $user): bool
    {
        // Admin sudah diizinkan oleh metode 'before'.
        // Untuk pengguna non-admin, cek apakah mereka memiliki permission spesifik.
        return $user->hasPermissionTo('reject registrations');
    }

    /**
     * Determine whether the user can manage users (e.g., view, create, update, delete others).
     * Metode ini akan dipanggil HANYA jika 'before' mengembalikan null.
     * (Digunakan oleh `$this->authorize('manage users');` di destroy/index/dll.)
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function manageUsers(User $user): bool
    {
        // Admin sudah diizinkan oleh metode 'before'.
        // Untuk pengguna non-admin, cek apakah mereka memiliki permission spesifik.
        return $user->hasPermissionTo('manage users');
    }
}