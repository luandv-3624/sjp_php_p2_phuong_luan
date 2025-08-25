<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Venue;

class VenuePolicy
{
    public function isOwner(User $user, Venue $venue): bool
    {
        return $user->id === $venue->owner_id;
    }

    public function update(User $user, Venue $venue): bool
    {
        return $this->isOwner($user, $venue);
    }

    public function delete(User $user, Venue $venue): bool
    {
        return $this->isOwner($user, $venue);
    }

    public function addManager(User $user, Venue $venue): bool
    {
        return $this->isOwner($user, $venue);
    }
}
