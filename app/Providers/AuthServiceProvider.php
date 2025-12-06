<?php

namespace App\Providers;

use App\Models\Product;
use App\Policies\Productpolicy;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',

        Product::class => Productpolicy::class,
    ];
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
