<?php

namespace App\Policies;

use App\Models\HasilVikor;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class HasilVikorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, HasilVikor $hasilVikor): bool
    {
        return $user->hasRole('admin') || 
           ($user->hasRole('siswa') && $hasilVikor->alternatif->user_id == $user->id);
    }

    public function cetak(User $user, HasilVikor $hasilVikor)
    {
        return $this->view($user, $hasilVikor);
    }
        /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, HasilVikor $hasilVikor): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, HasilVikor $hasilVikor): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, HasilVikor $hasilVikor): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, HasilVikor $hasilVikor): bool
    {
        //
    }
}
