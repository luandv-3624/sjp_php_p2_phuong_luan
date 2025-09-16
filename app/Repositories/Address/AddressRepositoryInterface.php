<?php

namespace App\Repositories\Address;

use Illuminate\Support\Collection;


interface AddressRepositoryInterface
{
    public function findAllProvinces(): Collection;
    public function findAllWards(int $provinceId): Collection;
}
