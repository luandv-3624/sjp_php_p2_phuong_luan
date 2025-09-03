<?php

namespace Database\Factories;

use App\Enums\SpaceStatus;
use App\Enums\VenueStatus;
use App\Models\PriceType;
use App\Models\SpaceType;
use App\Models\User;
use App\Models\Venue;
use App\Models\Ward;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class SpaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'venue_id' => Venue::inRandomOrder()->first()->id ?? Venue::factory(),
            'name' => $this->faker->word . ' Space',
            'space_type_id' => SpaceType::inRandomOrder()->first()->id,
            'capacity' => $this->faker->numberBetween(10, 200),
            'price_type_id' => PriceType::inRandomOrder()->first()->id,
            'price' => $this->faker->randomFloat(2, 50, 1000),
            'description' => $this->faker->sentence,
            'status' => $this->faker->randomElement(SpaceStatus::values()),
        ];
    }
}
