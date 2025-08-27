<?php

namespace App\Repositories\Booking;

use App\Models\Booking;
use Illuminate\Pagination\LengthAwarePaginator;

interface BookingRepositoryInterface
{
    public function create(array $data): Booking;
    public function findById(int $id): Booking;
    public function findAll(array $filters, ?int $pageSize): LengthAwarePaginator;
    public function findBookingForUpdate(int $id): ?Booking;
    public function updateBookingPaymentStatus(Booking $booking, array $bookingUpdateData): Booking;
}
