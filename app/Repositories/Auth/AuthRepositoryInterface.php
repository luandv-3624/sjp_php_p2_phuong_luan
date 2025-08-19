<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

interface AuthRepositoryInterface
{
    public function attemptLogin(array $credentials): bool;
    public function getAuthenticatedUser(): ?User;
    public function deleteUserTokens(User $user, string $tokenName): void;
    public function createToken(User $user, string $name, array $abilities, Carbon $expiresAt): array;
    public function findRefreshToken(string $token): ?PersonalAccessToken;
    public function deleteToken(PersonalAccessToken $token): void;
}
