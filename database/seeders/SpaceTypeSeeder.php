<?php

namespace Database\Seeders;

use App\Models\SpaceType;
use Illuminate\Database\Seeder;

class SpaceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['name' => 'Private Office', 'description' => 'A private office space for individual or team use.'],
            ['name' => 'Working Desk', 'description' => 'A single working desk in a shared environment.'],
            ['name' => 'Meeting Space', 'description' => 'A space designed for meetings and collaboration.'],
        ];

        foreach ($types as $type) {
            SpaceType::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}
