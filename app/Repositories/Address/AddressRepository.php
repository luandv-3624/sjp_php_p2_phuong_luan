<?php

namespace App\Repositories\Address;

use App\Models\Province;
use App\Models\Ward;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;


class AddressRepository implements AddressRepositoryInterface
{
    public function findAllProvinces(): Collection
    {
        return Cache::remember('provinces', 1440, function () {
            return Province::select('id', 'name', 'name_en')
                ->orderBy('name')
                ->get();
        });
    }

    public function findAllWards(int $provinceId): Collection
    {
        return Cache::remember("wards_{$provinceId}", 1440, function () use ($provinceId) {
            return Ward::select('id', 'name', 'name_en')
                ->where('province_id', $provinceId)
                ->orderBy('name')
                ->get();
        });
    }
}
