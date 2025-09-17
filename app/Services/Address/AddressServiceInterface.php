<?php

namespace App\Services\Address;

use Illuminate\Http\JsonResponse;

interface AddressServiceInterface
{
    public function findAllProvinces(): JsonResponse;
    public function findAllWards(int $provinceId): JsonResponse;
}
