<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class GoogleAuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            return $this->authService->loginWithGoogle([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
            ]);
        } catch (\Exception $e) {
            return ApiResponse::error(message: __('auth.unable_auth_google'), code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
