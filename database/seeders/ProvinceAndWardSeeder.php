<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;

class ProvinceAndWardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $url = env('PROVINCE_WARD_JSON_URL');

        if (empty($url)) {
            throw new \RuntimeException('The PROVINCE_WARD_JSON_URL is not set in the .env file.');
        }

        try {
            $response = Http::timeout(15)->retry(3, 200)->get($url);

            if (! $response->successful()) {
                throw new \RuntimeException("Failed to fetch data from {$url}, status: {$response->status()}");
            }

            $data = $response->json();

            if (! is_array($data)) {
                throw new \UnexpectedValueException("Invalid JSON response from {$url}");
            }

            DB::transaction(function () use ($data) {
                DB::table('wards')->delete();
                DB::table('provinces')->delete();

                $allWards = [];

                foreach ($data as $provinceData) {
                    $province = Province::create([
                        'code' => Arr::get($provinceData, 'Code'),
                        'name' => Arr::get($provinceData, 'Name'),
                        'name_en' => Arr::get($provinceData, 'NameEn'),
                        'full_name' => Arr::get($provinceData, 'FullName'),
                        'full_name_en' => Arr::get($provinceData, 'FullNameEn'),
                    ]);

                    if (! $province) {
                        throw new \RuntimeException("Failed to insert province: " . json_encode($provinceData));
                    }

                    foreach (Arr::get($provinceData, 'Wards', []) as $wardData) {
                        $allWards[] = [
                            'code' => Arr::get($wardData, 'Code'),
                            'province_id' => $province->id,
                            'name' => Arr::get($wardData, 'Name'),
                            'name_en' => Arr::get($wardData, 'NameEn'),
                            'full_name' => Arr::get($wardData, 'FullName'),
                            'full_name_en' => Arr::get($wardData, 'FullNameEn'),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                collect($allWards)->chunk(1000)->each(function ($chunk) {
                    DB::table('wards')->insert($chunk->toArray());
                });
            });
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to seed provinces and wards: ' . $e->getMessage(), 0, $e);
        }
    }
}
