<?php

namespace App\Repositories\Amenity;

use App\Models\Amenity;

interface AmenityRepositoryInterface
{
    public function create(array $data): Amenity;
    public function findById(int $id): ?Amenity;
    public function update(int $id, array $data): ?Amenity;
    public function delete(int $id): bool;
    public function getByVenue(int $venueId, array $filters, ?int $pageSize);
}
