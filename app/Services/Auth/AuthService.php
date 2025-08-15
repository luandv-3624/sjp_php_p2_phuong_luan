<?php

namespace App\Services\Auth;

use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Helpers\ApiResponse;
use App\Enums\HttpStatusCode;
use Carbon\Carbon;

class AuthService
{
    protected $authRepo;
    protected $userRepo;

    public function __construct(AuthRepositoryInterface $authRepo, UserRepositoryInterface $userRepo)
    {
        $this->authRepo = $authRepo;
        $this->userRepo = $userRepo;
    }

    public function login(array $credentials)
    {
        if (!$this->authRepo->attemptLogin($credentials)) {
            return ApiResponse::error(__('auth.login_failed'), [], HttpStatusCode::UNAUTHORIZED);
        }

        $user = $this->authRepo->getAuthenticatedUser();

        $this->authRepo->deleteUserTokens($user, 'access_token');

        $accessToken  = $this->authRepo->createToken($user, 'access_token', ['*'], Carbon::now()->addHours(1));
        $refreshToken = $this->authRepo->createToken($user, 'refresh_token', ['refresh'], Carbon::now()->addDays(15));

        return ApiResponse::success([
            'access_token'             => $accessToken['plainTextToken'],
            'access_token_expires_at'  => $accessToken['expiresAt'],
            'refresh_token'            => $refreshToken['plainTextToken'],
            'refresh_token_expires_at' => $refreshToken['expiresAt'],
            'token_type'               => 'Bearer',
        ], __('auth.login_success'));
    }

    public function refreshToken(string $currentRefreshToken)
    {
        $refreshToken = $this->authRepo->findRefreshToken($currentRefreshToken);

        if (
            !$refreshToken ||
            !$refreshToken->can('refresh') ||
            Carbon::parse($refreshToken->expires_at)->isPast()
        ) {
            return ApiResponse::error(__('auth.refresh_token_invalid'), [], HttpStatusCode::UNAUTHORIZED);
        }

        $user = $refreshToken->tokenable;
        $this->authRepo->deleteToken($refreshToken);

        $newAccessToken  = $this->authRepo->createToken($user, 'access_token', ['*'], Carbon::now()->addDays(1));
        $newRefreshToken = $this->authRepo->createToken($user, 'refresh_token', ['refresh'], Carbon::now()->addDays(7));

        return ApiResponse::success([
            'access_token'             => $newAccessToken['plainTextToken'],
            'access_token_expires_at'  => $newAccessToken['expiresAt'],
            'refresh_token'            => $newRefreshToken['plainTextToken'],
            'refresh_token_expires_at' => $newRefreshToken['expiresAt'],
            'token_type'               => 'Bearer',
        ], __('auth.refresh_token_success'));
    }

    public function logout($user)
    {
        $user->tokens()->delete();
        return ApiResponse::success([], __('auth.logout_success'));
    }
}
