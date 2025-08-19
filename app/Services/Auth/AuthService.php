<?php

namespace App\Services\Auth;

use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Auth\AuthRepositoryInterface;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Enums\HttpStatusCode;
use App\Enums\Role;
use App\Helpers\ApiResponse;
use App\Mail\VerifyEmail;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthService
{
    protected $authRepo;
    protected $userRepo;
    protected $accessTokenExpiredTime;
    protected $refreshTokenExpiredTime;
    private const TOKEN_VERIFY_ACCOUNT_EXPIRED_TIME = 15;

    public function __construct(AuthRepositoryInterface $authRepo, UserRepositoryInterface $userRepo)
    {
        $this->authRepo = $authRepo;
        $this->userRepo = $userRepo;
        $this->accessTokenExpiredTime = config('auth.access_token_expired_time');
        $this->refreshTokenExpiredTime = config('auth.refresh_token_expired_time');
    }

    public function login(array $credentials)
    {
        if (!$this->authRepo->attemptLogin($credentials)) {
            return ApiResponse::error(__('auth.login_failed'), [], HttpStatusCode::UNAUTHORIZED);
        }

        $user = $this->authRepo->getAuthenticatedUser();

        $this->authRepo->deleteUserTokens($user, 'access_token');

        $accessToken  = $this->authRepo->createToken($user, 'access_token', ['*'], Carbon::now()->addHours($this->accessTokenExpiredTime));
        $refreshToken = $this->authRepo->createToken($user, 'refresh_token', ['refresh'], Carbon::now()->addDays($this->refreshTokenExpiredTime));

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

        $newAccessToken  = $this->authRepo->createToken($user, 'access_token', ['*'], Carbon::now()->addHours($this->accessTokenExpiredTime));
        $newRefreshToken = $this->authRepo->createToken($user, 'refresh_token', ['refresh'], Carbon::now()->addDays($this->refreshTokenExpiredTime));

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

    public function signup(array $data)
    {
        if ($this->userRepo->findByEmail($data['email'])) {
            return ApiResponse::error(__('auth.email_already_exists'), [], HttpStatusCode::BAD_REQUEST);
        }

        $role = $this->userRepo->getRoleByName(ROLE::USER);
        if (!$role) {
            return ApiResponse::error(__('auth.role_not_found'), [], HttpStatusCode::BAD_REQUEST);
        }

        $user = $this->userRepo->create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => Hash::make($data['password']),
            'role_id'  => $role->id,
        ]);

        $token = Str::random(64);
        DB::table('user_verifications')->insert([
            'user_id'    => $user->id,
            'token'      => $token,
            'expires_at' => now()->addHours(self::TOKEN_VERIFY_ACCOUNT_EXPIRED_TIME),
            'created_at' => now(),
        ]);

        Mail::to($user->email)->send(new VerifyEmail($user, $token));

        return ApiResponse::success(['user' => $user,], __('auth.signup_success_check_email'), HttpStatusCode::CREATED);
    }

    public function verifyAccount(string $token)
    {
        $record = DB::table('user_verifications')->where('token', $token)->first();

        if (!$record) {
            return ApiResponse::error(__('auth.verify_failed'), [], HttpStatusCode::BAD_REQUEST);
        }

        if (Carbon::parse($record->expires_at)->isPast()) {
            return ApiResponse::error(__('auth.verify_link_expired'), [], HttpStatusCode::BAD_REQUEST);
        }

        $success = $this->userRepo->verifyUser($record->user_id);

        if (!$success) {
            return ApiResponse::error(__('auth.verify_failed'));
        }

        DB::table('user_verifications')->where('token', $token)->delete();

        return ApiResponse::success([], __('auth.verify_success'));
    }

    public function resendVerification(string $email)
    {
        $user = $this->userRepo->findByEmail($email);

        if (!$user || $user->status === 'verified') {
            return ApiResponse::error(__('auth.user_not_found_or_verified'), [], HttpStatusCode::BAD_REQUEST);
        }

        DB::table('user_verifications')->where('user_id', $user->id)->delete();

        $token = Str::random(64);
        DB::table('user_verifications')->insert([
            'user_id'    => $user->id,
            'token'      => $token,
            'expires_at' => now()->addHours(self::TOKEN_VERIFY_ACCOUNT_EXPIRED_TIME),
            'created_at' => now(),
        ]);

        Mail::to($user->email)->send(new VerifyEmail($user, $token));

        return ApiResponse::success([], __('auth.resend_verification_success'));
    }
}
