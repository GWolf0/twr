<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\BookingService;
use App\Services\CRUD\MasterCRUDService;
use App\Services\CRUD\UserCRUDService;
use App\Services\FileUploadService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // register auth service
        $this->app->singleton(AuthService::class);

        // register crud services
        $this->app->singleton(MasterCRUDService::class);
        $this->app->singleton(UserCRUDService::class);

        // register other services
        $this->app->singleton(FileUploadService::class);
        $this->app->singleton(BookingService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
