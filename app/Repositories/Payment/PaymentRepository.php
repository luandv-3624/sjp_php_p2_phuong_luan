<?php

namespace App\Repositories\Payment;

use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use App\Enums\PaymentStatus;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function createPayment(array $data): Payment
    {
        try {
            return Payment::create($data);
        } catch (\Exception $e) {
            Log::error('createPayment failed: ' . $e->getMessage(), [
                'data'  => $data,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function existsByTransId(string $transId): bool
    {
        try {
            return Payment::where('trans_id', $transId)->exists();
        } catch (\Exception $e) {
            Log::error('existsByTransId failed: ' . $e->getMessage(), [
                'trans_id' => $transId,
                'trace'    => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function getPaidAmountByBookingId(int $bookingId): int
    {
        try {
            return Payment::where('booking_id', $bookingId)
                ->where('status', PaymentStatus::SUCCESS->value)
                ->sum('amount');
        } catch (\Exception $e) {
            Log::error('getPaidAmountByBookingId failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
