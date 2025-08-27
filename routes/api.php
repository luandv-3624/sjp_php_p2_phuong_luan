<?php

use App\Http\Controllers\Api\SpaceController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Venue\VenueController;
use App\Http\Controllers\Amenity\AmenityController;
use App\Http\Controllers\Payment\PaymentController;
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

Route::middleware(['auth:sanctum', 'checkAccessTokenExpiry'])->group(function () {
    Route::prefix('venues')->group(function () {
        Route::post('/', [VenueController::class, 'store']);
        Route::put('/{venue}', [VenueController::class, 'update']);
        Route::delete('/{venue}', [VenueController::class, 'destroy']);
        Route::get('/{venue}', [VenueController::class, 'show']);
        Route::post('/{venue}/managers', [VenueController::class, 'addManager']);

        Route::put('/{venue}/status', [VenueController::class, 'updateStatusVenue'])
                ->middleware('role:admin,moderator');
        Route::get('/{venue}/amenities', [AmenityController::class, 'listByVenue']);

        Route::post('/{venue}/spaces', [SpaceController::class, 'store'])
            ->can('createSpace', 'venue');
        Route::get('/{venue}/spaces', [SpaceController::class, 'indexByVenue']);
    });

    Route::prefix('spaces')->group(function () {
        Route::put('/{space}', [SpaceController::class, 'update'])
            ->can('update', 'space');
        Route::get('/{space}', [SpaceController::class, 'show']);
    });

    Route::prefix('amenities')->group(function () {
        Route::post('/', [AmenityController::class, 'store']);
        Route::put('/{amenity}', [AmenityController::class, 'update']);
        Route::delete('/{amenity}', [AmenityController::class, 'destroy']);
        Route::get('/{amenity}', [AmenityController::class, 'show']);
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [AuthController::class, 'showProfile']);
        Route::put('/', [AuthController::class, 'updateProfile']);
    });

    Route::prefix('bookings')->group(function () {
        Route::post('/', [BookingController::class, 'store']);
        Route::get('/', [BookingController::class, 'index'])->middleware('role:admin,moderator');
        Route::get('/{booking}', [BookingController::class, 'show'])->can('view', 'booking');
    });

    Route::prefix('payment')->group(function () {
        Route::post('/momo', [PaymentController::class, 'payWithMomo'])->middleware('throttle:momo-user');
    });
});

Route::get('/payment/momo/redirect', [PaymentController::class, 'momoRedirect'])->name('payment.momo.redirect');
Route::post('/payment/momo/ipn', [PaymentController::class, 'momoIpn'])
    ->middleware('throttle:momo-ipn')
    ->name('payment.momo.ipn');
