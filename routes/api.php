<?php

use App\Http\Controllers\Api\UserController;
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
    Route::post('resend-verification', [AuthController::class, 'resendVerification'])
            ->middleware('throttle:resend-verification')->name('auth.resend');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('auth.refresh');
    Route::post('password/forgot-password', [AuthController::class, 'requestPasswordReset'])
        ->middleware('throttle:password-reset');
    Route::post('password/reset', [AuthController::class, 'resetPassword']);
    Route::middleware(['auth:sanctum', 'checkAccessTokenExpiry'])->post('logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::prefix('users')->middleware(['auth:sanctum', 'checkAccessTokenExpiry', 'role:admin,moderator'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update')->middleware(['role:admin'])->can('update', 'user');
    Route::put('/{user}/status', [UserController::class, 'updateStatus'])->name('users.update-status')->can('update', 'user');
});
