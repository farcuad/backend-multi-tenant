<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function ($user, string $token) {
        // Aquí pones la URL de tu frontend (React/Vue/etc)
        return 'https://gestion-productos-86199.web.app/reset-password?token='.$token.'&email='.$user->email;
    });
    }
}
