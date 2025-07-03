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
        if ($user->hasRole('admin')) {
            return true; // Izinkan akses penuh
        }

        // Jika pengguna BUKAN admin, kembalikan null untuk memungkinkan
        // metode policy spesifik (misalnya, 'update', 'view') untuk menangani otorisasi.
        return null;
    }

    /**
     * Determine whether the user can view the model.
     * Metode ini akan dipanggil HANYA jika 'before' mengembalikan null.
     *
     * @param  \App\Models\User  $authenticatedUser
     * @param  \App\Models\User  $userToView
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $authenticatedUser, User $userToView): bool
    {
        // Pengguna biasa dapat melihat profil mereka sendiri.
        return $authenticatedUser->id === $userToView->id;
        // Admin sudah ditangani oleh metode 'before'.
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
        // Pengguna biasa dapat memperbarui profil mereka sendiri.
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
        // Pencegahan admin menghapus akunnya sendiri (meskipun 'before' mengizinkan admin menghapus orang lain)
        if ($authenticatedUser->id === $userToDelete->id) {
            return Response::deny('Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Jika metode `before` sudah ada, ini hanya akan berlaku untuk non-admin.
        // Jika non-admin mencoba menghapus, mereka tidak diizinkan.
        return false;
    }

    /**
     * Determine whether the user can approve registrations.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function approveRegistrations(User $user): bool
    {
        // Admin sudah ditangani oleh metode 'before'.
        // Jika Anda ingin ini lebih eksplisit atau untuk peran lain, Anda bisa memeriksa izin di sini.
        return $user->hasPermissionTo('approve registrations');
    }

    /**
     * Determine whether the user can reject registrations.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function rejectRegistrations(User $user): bool
    {
        return $user->hasPermissionTo('reject registrations');
    }

    /**
     * Determine whether the user can manage users.
     * (Digunakan oleh `$this->authorize('manage users');` di destroy)
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function manageUsers(User $user): bool
    {
         return $user->hasPermissionTo('manage users');
    }

    /**
     * Determine whether the user can create new users.
     * (Tambahkan ini jika Anda berencana memiliki metode 'create' dan 'store' di UserController)
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create users');
    }
}