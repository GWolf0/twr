<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\BookingService;
use App\Services\CRUD\BookingCRUDService;
use App\Services\CRUD\MasterCRUDService;
use App\Services\CRUD\MediaCRUDService;
use App\Services\CRUD\SettingCRUDService;
use App\Services\CRUD\UserCRUDService;
use App\Services\CRUD\VehicleCRUDService;
use App\Services\FileUploadService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
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
        $this->app->singleton(VehicleCRUDService::class);
        $this->app->singleton(BookingCRUDService::class);
        $this->app->singleton(SettingCRUDService::class);
        $this->app->singleton(MediaCRUDService::class);

        // register other services
        $this->app->singleton(FileUploadService::class);
        $this->app->singleton(BookingService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // use "auth.page.reset_password" to create password reset url
        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            return route('auth.page.reset_password', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);
        });

        // use "auth.page.confirm_email" to create email confirmation url
        VerifyEmail::createUrlUsing(function ($notifiable) {
            return URL::temporarySignedRoute(
                'auth.action.confirm_email',
                now()->addMinutes(60),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
        });
    }
}
