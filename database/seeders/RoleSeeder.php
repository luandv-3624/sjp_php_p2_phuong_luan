<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'user',
                'description' => 'Normal user who can book spaces'
            ],
            [
                'name' => 'moderator',
                'description' => 'Moderator who can manage bookings and venues'
            ],
            [
                'name' => 'admin',
                'description' => 'Administrator with full access'
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
    }
}
