<?php

namespace App\Repositories\Address;

use App\Models\Province;
use App\Models\Ward;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;


class AddressRepository implements AddressRepositoryInterface
{
    public function findAllProvinces(): Collection
    {
        $locale = App::getLocale();

        return Cache::remember('provinces_' . $locale, 1440, function () use ($locale) {
            return Province::select('id', 'name', 'name_en')
                ->orderBy('name')
                ->get()
                ->map(function ($province) use ($locale) {
                    return [
                        'id' => $province->id,
                        'name' => $locale === 'en' ? $province->name_en : $province->name,
                    ];
                });
        });
    }

    public function findAllWards(int $provinceId): Collection
    {
        $locale = App::getLocale();

        return Cache::remember("wards_{$provinceId}_" . $locale, 1440, function () use ($provinceId, $locale) {
            return Ward::select('id', 'name', 'name_en')
                ->where('province_id', $provinceId)
                ->orderBy('name')
                ->get()
                ->map(function ($ward) use ($locale) {
                    return [
                        'id' => $ward->id,
                        'name' => $locale === 'en' ? $ward->name_en : $ward->name,
                    ];
                });
        });
    }
}
