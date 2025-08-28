<?php

namespace Database\Factories;

use App\Enums\VenueStatus;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class VenueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'owner_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'name' => $this->faker->company . ' Venue',
            'address' => $this->faker->address,
            'ward_id' => Ward::inRandomOrder()->first()->id,
            'lat' => $this->faker->latitude(-90, 90),
            'lng' => $this->faker->longitude(-180, 180),
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(VenueStatus::values()),
        ];
    }
}
