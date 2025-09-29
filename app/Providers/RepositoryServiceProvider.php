<?php

namespace App\Providers;

use App\Repositories\Address\AddressRepository;
use App\Repositories\Address\AddressRepositoryInterface;
use App\Services\Address\AddressService;
use App\Services\Address\AddressServiceInterface;
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
use App\Repositories\Payment\PaymentRepositoryInterface;
use App\Repositories\Payment\PaymentRepository;
use App\Services\Payment\PaymentServiceInterface;
use App\Services\Payment\PaymentService;
use App\Repositories\Notification\NotificationRepositoryInterface;
use App\Repositories\Notification\NotificationRepository;
use App\Services\Notification\NotificationServiceInterface;
use App\Services\Notification\NotificationService;

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
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(PaymentServiceInterface::class, PaymentService::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(NotificationServiceInterface::class, NotificationService::class);
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
