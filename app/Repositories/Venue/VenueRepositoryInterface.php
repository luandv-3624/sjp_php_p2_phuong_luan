<?php

namespace App\Repositories\Venue;

use Illuminate\Pagination\LengthAwarePaginator;

interface VenueRepositoryInterface
{
    public function create(array $data);
    public function findAll(array $filters, ?int $pageSize): LengthAwarePaginator;

    public function update(int $venueId, array $data);

    public function delete(int $venueId);

    public function findById(int $venueId);

    public function findByIdCustomer(int $venueId);

    public function addManager(int $venueId, array $userIds);

    public function findAllByUser(int $userId);
}
