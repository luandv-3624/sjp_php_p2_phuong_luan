<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateProfileRequest;
use Illuminate\Http\Request;
use App\Services\Auth\AuthService;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        return $this->authService->login($request->only('email', 'password'));
    }

    public function refreshToken(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string',
        ]);
        return $this->authService->refreshToken($request->input('refresh_token'));
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request->user());
    }

    public function signup(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:20|regex:/^[0-9\-\+\(\)\s]*$/',
            'password' => 'required|min:6',
        ]);

        return $this->authService->signup($data);
    }

    public function verify(Request $request)
    {
        return $this->authService->verifyAccount($request->query('token'));
    }

    public function resendVerification(Request $request)
    {
        $data = $request->validate(['email' => 'required|email']);

        return $this->authService->resendVerification($data['email']);
    }

    public function requestPasswordReset(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        return $this->authService->requestPasswordReset($data['email']);

    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|exists:users,email',
            'token'    => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        return $this->authService->resetPassword($data['email'], $data['token'], $data['password']);
    }

    public function showProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->authService->findOne($user->id);
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        return $this->authService->updateOne($user->id, $request->validated());
    }
}
