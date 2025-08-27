<?php

namespace App\Services\Booking;

use Illuminate\Http\JsonResponse;

interface BookingServiceInterface
{
    public function create(array $data): JsonResponse;
    public function findById(int $id): JsonResponse;
    public function findAll(array $filters, ?int $pageSize): JsonResponse;
}
