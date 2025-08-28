<?php

namespace App\Services\Space;

use Illuminate\Http\JsonResponse;

interface SpaceServiceInterface
{
    public function create(array $data): JsonResponse;
    public function updateById(int $id, array $data): JsonResponse;
    public function findById(int $id): JsonResponse;
    public function findAllByVenue(int $venueId): JsonResponse;
    public function findAll(array $filters, ?int $pageSize): JsonResponse;
}
