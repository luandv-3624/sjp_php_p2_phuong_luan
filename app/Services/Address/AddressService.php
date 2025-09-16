<?php

namespace App\Services\Address;

use App\Helpers\ApiResponse;
use App\Repositories\Address\AddressRepositoryInterface;
use Illuminate\Http\JsonResponse;

class AddressService implements AddressServiceInterface
{
    public function __construct(private AddressRepositoryInterface $addressRepositoryInterface)
    {
    }

    public function findAllProvinces(): JsonResponse
    {
        return ApiResponse::success($this->addressRepositoryInterface->findAllProvinces());
    }
    public function findAllWards(int $provinceId): JsonResponse
    {
        return ApiResponse::success($this->addressRepositoryInterface->findAllWards($provinceId));
    }
}
