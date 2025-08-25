<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $targetUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $targetUser): bool
    {
        // A user cannot update another user
        if ($user->role->name === Role::USER) {
            return false;
        }

        // Users cannot update themselves
        if ($user->id === $targetUser->id) {
            return false;
        }

        switch ($targetUser->role->name) {
            // Prevent updating an admin user
            case Role::ADMIN:
                return false;

            // Only an admin can update a moderator
            case Role::MODERATOR:
                return $user->role->name === Role::ADMIN;
        }

        // If none of the above conditions are met, the update is allowed
        return true;
    }
}
