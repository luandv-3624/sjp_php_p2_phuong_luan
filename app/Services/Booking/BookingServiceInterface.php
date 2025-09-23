<?php

namespace App\Services\Booking;

use Illuminate\Http\JsonResponse;
use App\Models\Booking;
use App\Models\User;

interface BookingServiceInterface
{
    public function create(array $data): JsonResponse;
    public function findById(int $id): JsonResponse;
    public function findAll(array $filters, ?int $pageSize): JsonResponse;
    public function findAllByOM(array $filters, ?int $pageSize, User $currentUser): JsonResponse;
    public function updateStatus(Booking $booking, string $newStatus): JsonResponse;
    public function checkIn(int $bookingId): JsonResponse;
    public function checkOut(int $bookingId): JsonResponse;
}
