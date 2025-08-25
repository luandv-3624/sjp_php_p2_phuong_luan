<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\Auth\PasswordResetRepositoryInterface;
use App\Repositories\Auth\PasswordResetRepository;
use App\Services\User\UserServiceInterface;
use App\Services\User\UserService;
use App\Repositories\Venue\VenueRepositoryInterface;
use App\Repositories\Venue\VenueRepository;

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
