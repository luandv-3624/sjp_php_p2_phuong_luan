<?php

namespace App\Repositories\Venue;

use App\Models\Venue;

interface VenueRepositoryInterface
{
    public function create(array $data);

    public function update(int $venueId, array $data);

    public function delete(int $venueId);

    public function findById(int $venueId);

    public function findByIdCustomer(int $venueId);

    public function addManager(int $venueId, array $userIds);
}
