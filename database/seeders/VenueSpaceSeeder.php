<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Space;
use App\Models\Venue;

class VenueSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Venue::factory()
            ->count(10)
            ->create()
            ->each(function ($venue) {
                Space::factory()
                    ->count(rand(3, 5))
                    ->create([
                        'venue_id' => $venue->id,
                    ]);
            });
    }
}
