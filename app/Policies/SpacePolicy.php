<?php

namespace App\Policies;

use App\Models\Space;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpacePolicy
{
    use HandlesAuthorization;

    private function isOwner(User $user, Venue $venue): bool
    {
        return $venue->owner_id === $user->id;
    }

    private function isManager(User $user, Venue $venue): bool
    {
        return $venue->managers()
            ->where('user_id', $user->id)
            ->exists();
    }

    private function isOwnerOrManager(User $user, Venue $venue): bool
    {
        return $this->isOwner($user, $venue) || $this->isManager($user, $venue);
    }

    public function update(User $user, Space $space)
    {
        return $this->isOwnerOrManager($user, $space->venue);
    }
}
