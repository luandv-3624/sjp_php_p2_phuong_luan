<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\Auth\PasswordResetRepositoryInterface;
use App\Repositories\Auth\PasswordResetRepository;
use App\Repositories\Space\SpaceRepository;
use App\Repositories\Space\SpaceRepositoryInterface;
use App\Repositories\Booking\BookingRepository;
use App\Repositories\Booking\BookingRepositoryInterface;
use App\Services\User\UserServiceInterface;
use App\Services\User\UserService;
use App\Repositories\Venue\VenueRepositoryInterface;
use App\Repositories\Venue\VenueRepository;
use App\Repositories\Amenity\AmenityRepositoryInterface;
use App\Repositories\Amenity\AmenityRepository;
use App\Services\Space\SpaceService;
use App\Services\Space\SpaceServiceInterface;
use App\Services\Booking\BookingService;
use App\Services\Booking\BookingServiceInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PasswordResetRepositoryInterface::class, PasswordResetRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(VenueRepositoryInterface::class, VenueRepository::class);
        $this->app->bind(AmenityRepositoryInterface::class, AmenityRepository::class);
        $this->app->bind(SpaceRepositoryInterface::class, SpaceRepository::class);
        $this->app->bind(SpaceServiceInterface::class, SpaceService::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(BookingServiceInterface::class, BookingService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
