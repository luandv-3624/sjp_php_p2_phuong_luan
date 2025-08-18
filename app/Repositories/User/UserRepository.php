<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Facades\Log;

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
                'status'            => 'verified',
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
}
