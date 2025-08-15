<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Auth\AuthService;

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
        return $this->authService->refreshToken($request->bearerToken());
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request->user());
    }
}
