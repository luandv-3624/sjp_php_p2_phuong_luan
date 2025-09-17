<?php

namespace App\Http\Controllers\Api;

use App\Models\Province;
use App\Services\Address\AddressServiceInterface;

class AddressController extends BaseApiController
{
    public function __construct(private AddressServiceInterface $addressServiceInterface)
    {
    }

    public function provincesIndex()
    {
        return $this->addressServiceInterface->findAllProvinces();
    }

    public function wardsIndex(Province $province)
    {
        return $this->addressServiceInterface->findAllWards($province->id);
    }
}
