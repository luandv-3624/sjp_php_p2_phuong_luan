<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('signup', [AuthController::class, 'signup'])->name('auth.signup');
    Route::get('verify', [AuthController::class, 'verify'])->name('auth.verify');
    Route::post('resend-verification', [AuthController::class, 'resendVerification'])->name('auth.resend');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('auth.refresh');
    Route::middleware(['auth:sanctum', 'checkAccessTokenExpiry'])->post('logout', [AuthController::class, 'logout'])->name('auth.logout');
});
