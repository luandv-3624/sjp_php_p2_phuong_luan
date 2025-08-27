<?php

namespace App\Repositories\Payment;

use App\Models\Booking;
use App\Models\Payment;

interface PaymentRepositoryInterface
{
    public function createPayment(array $data): Payment;

    public function existsByTransId(string $transId): bool;
    public function getPaidAmountByBookingId(int $bookingId): int;
}
