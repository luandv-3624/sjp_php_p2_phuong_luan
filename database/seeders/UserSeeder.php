<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Nguyen Van A',
                'phone_number' => '0901000001',
                'email' => 'user1@example.com',
                'password' => Hash::make(env('DEFAULT_USER_PASSWORD', 'ChangeMe!123')),
                'status' => 'active',
                'role_id' => 1, // user
            ],
            [
                'name' => 'Tran Thi B',
                'phone_number' => '0901000002',
                'email' => 'user2@example.com',
                'password' => Hash::make(env('DEFAULT_USER_PASSWORD', 'ChangeMe!123')),
                'status' => 'active',
                'role_id' => 1, // user
            ],
            [
                'name' => 'Le Van C',
                'phone_number' => '0901000003',
                'email' => 'moderator@example.com',
                'password' => Hash::make(env('DEFAULT_USER_PASSWORD', 'ChangeMe!123')),
                'status' => 'active',
                'role_id' => 2, // moderator
            ],
            [
                'name' => 'Pham Thi D',
                'phone_number' => '0901000004',
                'email' => 'admin1@example.com',
                'password' => Hash::make(env('DEFAULT_USER_PASSWORD', 'ChangeMe!123')),
                'status' => 'active',
                'role_id' => 3, // admin
            ],
            [
                'name' => 'Hoang Van E',
                'phone_number' => '0901000005',
                'email' => 'admin2@example.com',
                'password' => Hash::make(env('DEFAULT_USER_PASSWORD', 'ChangeMe!123')),
                'status' => 'active',
                'role_id' => 3, // admin
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
