<?php

namespace App\Policies;

use App\Models\Transanction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TransanctionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Transanction $transanction): bool
    {
        return $transanction->project->user_id == auth()->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Transanction $transanction): bool
    {
        return $transanction->project->user_id ==  auth()->user()->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Transanction $transanction): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Transanction $transanction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Transanction $transanction): bool
    {
        return false;
    }
}
