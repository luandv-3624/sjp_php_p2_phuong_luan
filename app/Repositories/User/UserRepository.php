<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Enums\AccountStatus;

class UserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        try {
            return User::where('email', $email)->first();
        } catch (\Exception $e) {
            Log::error('Find by email failed: '.$e->getMessage(), [
                'email' => $email,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function create(array $data): User
    {
        try {
            return User::create($data);
        } catch (\Exception $e) {
            Log::error('User creation failed: '.$e->getMessage(), [
                'data'  => $data,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function verifyUser(int $userId): bool
    {
        try {
            return User::where('id', $userId)->update([
                'status'            => AccountStatus::VERIFIED,
                'email_verified_at' => now(),
            ]) > 0;
        } catch (\Exception $e) {
            Log::error('User verification failed: '.$e->getMessage(), [
                'user_id' => $userId,
                'trace'   => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getRoleByName(string $roleName): ?Role
    {
        try {
            return Role::where('name', $roleName)->first();
        } catch (\Exception $e) {
            Log::error('Get role by name failed: '.$e->getMessage(), [
                'role_name' => $roleName,
                'trace'     => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
