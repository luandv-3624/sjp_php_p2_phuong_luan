<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthRepository implements AuthRepositoryInterface
{
    public function attemptLogin(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    public function getAuthenticatedUser(): ?User
    {
        return Auth::user();
    }

    public function deleteUserTokens(User $user, string $tokenName): void
    {
        $user->tokens()->where('name', $tokenName)->delete();
    }

    public function createToken(User $user, string $name, array $abilities, Carbon $expiresAt): array
    {
        $tokenObj = $user->createToken($name, $abilities);
        $tokenObj->accessToken->expires_at = $expiresAt;
        $tokenObj->accessToken->save();

        return [
            'plainTextToken' => $tokenObj->plainTextToken,
            'expiresAt'      => $expiresAt
        ];
    }

    public function findRefreshToken(string $token): ?PersonalAccessToken
    {
        return PersonalAccessToken::findToken($token);
    }

    public function deleteToken(PersonalAccessToken $token): void
    {
        $token->delete();
    }
}
