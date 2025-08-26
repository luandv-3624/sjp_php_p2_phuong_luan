<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Venue;
use App\Models\Amenity;

class AmenityPolicy
{
    protected function isOwner(User $user, Venue $venue): bool
    {
        return $venue->owner_id === $user->id;
    }

    protected function isManager(User $user, Venue $venue): bool
    {
        return $venue->managers()
                ->where('user_id', $user->id)
                ->exists();
    }

    protected function isOwnerOrManager(User $user, Venue $venue): bool
    {
        return $this->isOwner($user, $venue) || $this->isManager($user, $venue);
    }

    public function create(User $user, Venue $venue): bool
    {
        return $this->isOwnerOrManager($user, $venue);
    }

    public function update(User $user, Amenity $amenity, ?Venue $newVenue = null): bool
    {
        $currentVenue = $amenity->venue;

        // If venue is not changed → only need permission on the current venue
        if ($newVenue === null || $newVenue->id === $currentVenue->id) {
            return $this->isOwnerOrManager($user, $currentVenue);
        }

        // If venue is changed → user must have permission on both current and target venues
        return $this->isOwnerOrManager($user, $currentVenue)
            && $this->isOwnerOrManager($user, $newVenue);
    }

    public function delete(User $user, Amenity $amenity): bool
    {
        return $this->isOwnerOrManager($user, $amenity->venue);
    }
}
