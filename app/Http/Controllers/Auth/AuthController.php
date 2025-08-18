<?php

namespace App\Http\Controllers\Auth;

use App\Enums\HttpStatusCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Auth\AuthService;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            return $this->authService->login($request->only('email', 'password'));

        } catch (\Exception $e) {
            Log::error('Login failed: '.$e->getMessage(), [
                'email' => $request->input('email'),
                'trace' => $e->getTraceAsString()
            ]);

            return ApiResponse::error('auth.internal_server_error', [], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function refreshToken(Request $request)
    {
        try {
            $request->validate([
                'refresh_token' => 'required|string',
            ]);
            return $this->authService->refreshToken($request->input('refresh_token'));
        } catch (\Exception $e) {
            Log::error('Refresh token failed: '.$e->getMessage(), [
                'token' => $request->bearerToken(),
                'trace' => $e->getTraceAsString()
            ]);

            return ApiResponse::error('auth.internal_server_error', [], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request)
    {
        try {
            return $this->authService->logout($request->user());
        } catch (\Exception $e) {
            Log::error('Logout failed: '.$e->getMessage(), [
                'user_id' => optional($request->user())->id,
                'trace'   => $e->getTraceAsString()
            ]);

            return ApiResponse::error('auth.internal_server_error', [], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function signup(Request $request)
    {
        try {
            $data = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'phone_number' => 'nullable|string|max:20|regex:/^[0-9\-\+\(\)\s]*$/',
                'password' => 'required|min:6',
            ]);

            return $this->authService->signup($data);
        } catch (\Exception $e) {
            Log::error('Signup failed: '.$e->getMessage(), [
                'email' => $request->input('email'),
                'trace' => $e->getTraceAsString()
            ]);

            return ApiResponse::error('auth.internal_server_error', [], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function verify(Request $request)
    {
        try {
            return $this->authService->verifyAccount($request->query('token'));
        } catch (\Exception $e) {
            Log::error('Verify account failed: '.$e->getMessage(), [
              'token' => $request->query('token'),
                'trace'   => $e->getTraceAsString()
            ]);

            return ApiResponse::error('auth.internal_server_error', [], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function resendVerification(Request $request)
    {
        try {
            $data = $request->validate(['email' => 'required|email']);

            return $this->authService->resendVerification($data['email']);
        } catch (\Exception $e) {
            Log::error('Resend verification failed: '.$e->getMessage(), [
                'email' => $request->input('email'),
                'trace' => $e->getTraceAsString()
            ]);

            return ApiResponse::error('auth.internal_server_error', [], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

}
