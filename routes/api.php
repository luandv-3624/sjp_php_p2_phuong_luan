<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Venue\VenueController;
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
});

Route::middleware(['auth:sanctum', 'checkAccessTokenExpiry'])->group(function () {
    Route::prefix('venues')->group(function () {
        Route::post('/', [VenueController::class, 'store']);
        Route::put('/{venue}', [VenueController::class, 'update']);
        Route::delete('/{venue}', [VenueController::class, 'destroy']);
        Route::get('/{venue}', [VenueController::class, 'show']);
        Route::post('/{venue}/managers', [VenueController::class, 'addManager']);

        Route::put('/{venue}/status', [VenueController::class, 'updateStatusVenue'])
                ->middleware('role:admin,moderator');
    });
});
