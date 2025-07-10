<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Criteria;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log; // Tetap gunakan Log untuk debugging

class CriteriaPolicy
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
        // mereka secara otomatis diizinkan untuk melakukan SEMUA kemampuan pada kriteria.
        if ($user->hasRole('admin')) {
            // Log::channel('policy')->debug("CriteriaPolicy: Admin user {$user->id} granted full access via 'before' method for ability '{$ability}'.");
            return true;
        }

        return null;
    }

    // --- Metode-metode di bawah ini HANYA akan dieksekusi jika method 'before' mengembalikan null (yaitu, untuk non-admin) ---

    public function viewAny(User $user): bool
    {
        // Untuk non-admin, cek izin 'criteria-list'
        return $this->checkPermission($user, 'criteria-list', 'viewAny');
    }

    public function view(User $user, Criteria $criteria): bool
    {
        // Untuk non-admin, cek izin 'criteria-list' atau jika mereka yang membuat kriteria
        return $this->checkPermission($user, 'criteria-list', 'view', $criteria) ||
               ($user->id === $criteria->created_by && $user->hasPermissionTo('criteria-list')); // Asumsi 'criteria-list' cukup untuk melihat yang dibuat sendiri
    }

    public function create(User $user): bool
    {
        // Untuk non-admin, cek izin 'criteria-create'
        return $this->checkPermission($user, 'criteria-create', 'create');
    }

    public function update(User $user, Criteria $criteria): bool
    {
        $canEdit = $this->checkPermission($user, 'criteria-edit', 'update', $criteria) ||
               ($user->id === $criteria->created_by &&
                $user->hasPermissionTo('edit-own-criteria'));
        
                return $canEdit;
    }

    public function delete(User $user, Criteria $criteria): bool
    {
        // Untuk non-admin, cek izin 'criteria-delete' atau jika mereka yang membuat kriteria dan punya izin delete-own
        return $this->checkPermission($user, 'criteria-delete', 'delete', $criteria) ||
               ($user->id === $criteria->created_by &&
                $user->hasPermissionTo('delete-own-criteria')); // Pastikan izin 'delete-own-criteria' ada dan diberikan jika ini diperlukan
    }

    public function restore(User $user, Criteria $criteria): bool
    {
        // Untuk non-admin, gunakan logika update
        return $this->update($user, $criteria);
    }

    public function forceDelete(User $user, Criteria $criteria): bool
    {
        // Untuk non-admin, gunakan logika delete
        return $this->delete($user, $criteria);
    }

    // Helper method untuk debugging dan konsistensi
    protected function checkPermission(User $user, string $permission, string $method, ?Criteria $criteria = null): bool
    {
        $result = $user->hasPermissionTo($permission);
        
        Log::channel('policy')->debug("CriteriaPolicy: checkPermission method '{$method}' for permission '{$permission}' on criteria_id " . ($criteria ? $criteria->id : 'N/A') . " by user {$user->id}. Result: " . ($result ? 'Allowed' : 'Denied'));
        
        return $result;
    }
}