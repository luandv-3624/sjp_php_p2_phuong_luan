<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            SpaceTypeSeeder::class,
            PriceTypeSeeder::class,
            SpaceTypeSeeder::class,
            ProvinceAndWardSeeder::class,
            VenueSpaceSeeder::class,
        ]);
    }
}
