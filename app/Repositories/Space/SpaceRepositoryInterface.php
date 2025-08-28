<?php

namespace App\Repositories\Space;

use App\Models\Space;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface SpaceRepositoryInterface
{
    public function create(array $data): Space;
    public function updateById(int $id, array $data): Space;
    public function findById(int $id): Space;
    public function findAllByVenue(int $venueId): Collection;
    public function findAll(array $filters, ?int $pageSize): LengthAwarePaginator;
}
