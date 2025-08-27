<?php

namespace App\Services\Payment;

use Illuminate\Http\JsonResponse;

interface PaymentServiceInterface
{
    public function payWithMomo(array $data, int $userId): JsonResponse;

    public function handleMomoIpn(array $data): JsonResponse;
}
