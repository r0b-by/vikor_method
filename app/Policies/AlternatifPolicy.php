<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Alternatif; // Perbaiki: Huruf besar A
use Illuminate\Auth\Access\Response;

class AlternatifPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Hanya admin atau guru yang bisa melihat daftar alternatif
        return $user->hasRole('admin') || $user->hasRole('guru');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Alternatif $alternatif): bool
    {
        // Hanya admin atau guru yang bisa melihat alternatif tertentu
        return $user->hasRole('admin') || $user->hasRole('guru');
        // Jika ada logika di mana siswa hanya bisa melihat alternatif mereka sendiri, tambahkan:
        // return ($user->hasRole('admin') || $user->hasRole('guru')) || ($user->hasRole('siswa') && $user->id === $alternatif->user_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Hanya admin atau guru yang bisa membuat alternatif
        return $user->hasRole('admin') || $user->hasRole('guru');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Alternatif $alternatif): bool
    {
        // Hanya admin atau guru yang bisa mengupdate alternatif
        return $user->hasRole('admin') || $user->hasRole('guru');
        // Jika ada logika di mana user yang membuat bisa mengedit, tambahkan:
        // return ($user->hasRole('admin') || $user->hasRole('guru')) || ($user->id === $alternatif->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Alternatif $alternatif): bool
    {
        // Hanya admin atau guru yang bisa menghapus alternatif
        return $user->hasRole('admin') || $user->hasRole('guru');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Alternatif $alternatif): bool
    {
        // Sesuaikan dengan kebutuhan restore, mungkin hanya admin
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Alternatif $alternatif): bool
    {
        // Biasanya hanya admin yang bisa melakukan force delete
        return $user->hasRole('admin');
    }
}