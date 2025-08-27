<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Booking;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    private function isVenueOwner(User $user, Venue $venue): bool
    {
        return $venue->owner_id === $user->id;
    }

    private function isVenueManager(User $user, Venue $venue): bool
    {
        return $venue->managers()
            ->where('user_id', $user->id)
            ->exists();
    }

    protected function isVenueOwnerOrManager(User $user, Venue $venue): bool
    {
        return $this->isVenueOwner($user, $venue) || $this->isVenueManager($user, $venue);
    }

    private function isBookingOwner(User $user, Booking $booking): bool
    {
        return $booking->user_id === $user->id;
    }

    private function isAdmin(User $user): bool
    {
        return $user->role->name === Role::ADMIN;
    }

    public function view(User $user, Booking $booking)
    {
        $booking->loadMissing('space.venue');

        $venue = $booking->space->venue;

        return $this->isAdmin($user) || $this->isVenueOwnerOrManager($user, $venue) || $this->isBookingOwner($user, $booking);
    }
}
