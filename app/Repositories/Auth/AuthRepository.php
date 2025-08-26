<?php

namespace App\Repositories\Auth;

use App\Enums\AccountStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthRepository implements AuthRepositoryInterface
{
    public function attemptLogin(array $credentials): bool
    {
        if (Auth::attempt($credentials)) {
            $user = User::where('email', $credentials['email'])->first();
            if ($user && $user->status !== AccountStatus::INACTIVE->value) {
                return true;
            }
        }

        return false;
    }

    public function getAuthenticatedUser(): ?User
    {
        return Auth::user();
    }

    public function deleteUserTokens(User $user, string $tokenName): void
    {
        try {
            $user->tokens()->where('name', $tokenName)->delete();
        } catch (\Exception $e) {
            Log::error('Delete user tokens failed: '.$e->getMessage(), [
                'user_id' => $user->id,
                'token_name' => $tokenName,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function createToken(User $user, string $name, array $abilities, Carbon $expiresAt): array
    {
        try {
            $tokenObj = $user->createToken($name, $abilities);
            $tokenObj->accessToken->expires_at = $expiresAt;
            $tokenObj->accessToken->save();

            return [
                'plainTextToken' => $tokenObj->plainTextToken,
                'expiresAt'      => $expiresAt
            ];
        } catch (\Exception $e) {
            Log::error('Create token failed: '.$e->getMessage(), [
                'user_id'    => $user->id,
                'token_name' => $name,
                'trace'      => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function findRefreshToken(string $token): ?PersonalAccessToken
    {
        try {
            return PersonalAccessToken::findToken($token);
        } catch (\Exception $e) {
            Log::error('Find refresh token failed: '.$e->getMessage(), [
                'token' => $token,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function deleteToken(PersonalAccessToken $token): void
    {
        try {
            $token->delete();
        } catch (\Exception $e) {
            Log::error('Delete token failed: '.$e->getMessage(), [
                'token_id' => $token->id ?? null,
                'trace'    => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function findOne(int $id): User
    {
        $user = User::find($id);

        if (!$user) {
            throw new NotFoundHttpException(__('auth.user_not_found'));
        }

        return $user;
    }
    public function updateOne(int $id, array $data): User
    {
        $user = $this->findOne($id);

        $fields = [
            'name',
            'phone_number'
        ];

        $updateData = [];

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        $user->update($updateData);

        return $user;
    }
}
