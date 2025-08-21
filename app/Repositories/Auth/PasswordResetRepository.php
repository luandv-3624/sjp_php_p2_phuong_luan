<?php

namespace App\Repositories\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PasswordResetRepository implements PasswordResetRepositoryInterface
{
    public function createToken(string $email, string $token): void
    {
        try {
            DB::table('password_resets')->updateOrInsert(
                ['email' => $email],
                [
                    'token'      => Hash::make($token),
                    'created_at' => now()
                ]
            );
        } catch (\Exception $e) {
            Log::error('Create password reset token failed: '.$e->getMessage(), [
                'email' => $email,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function findByToken(string $email, string $plainToken)
    {
        try {
            $reset = DB::table('password_resets')
                        ->where('email', $email)
                        ->first();

            if ($reset && Hash::check($plainToken, $reset->token)) {
                return $reset;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Find password reset token failed: '.$e->getMessage(), [
                'email' => $email,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function deleteByEmail(string $email): void
    {
        try {
            DB::table('password_resets')->where('email', $email)->delete();
        } catch (\Exception $e) {
            Log::error('Delete password reset token failed: '.$e->getMessage(), [
                'email' => $email,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
