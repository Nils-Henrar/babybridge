<?php

namespace App\Policies;

use App\Models\Section;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SectionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, $section)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user)
    {
        return $user->roles->contains('role', 'admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, $section)
    {
        return $user->roles->contains('role', 'admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, $section)
    {
        return $user->roles->contains('role', 'admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Section $section): bool
    {
        return $user->roles->contains('role', 'admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Section $section): bool
    {
        return $user->roles->contains('role', 'admin');
    }
}
