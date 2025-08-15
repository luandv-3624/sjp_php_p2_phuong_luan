<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PriceType;

class PriceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $priceTypes = [
            [
                'code' => 'month',
                'name' => 'Tháng',
                'name_en' => 'Month'
            ],
            [
                'code' => 'day',
                'name' => 'Ngày',
                'name_en' => 'Day'
            ],
            [
                'code' => 'hour',
                'name' => 'Giờ',
                'name_en' => 'Hour'
            ],
        ];

        foreach ($priceTypes as $type) {
            PriceType::updateOrCreate(
                ['code' => $type['code']],
                [
                    'name' => $type['name'],
                    'name_en' => $type['name_en']
                ]
            );
        }
    }
}
