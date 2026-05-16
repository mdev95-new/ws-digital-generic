<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Services\Payment\PaymentManager::class);
        $this->app->singleton(\App\Services\Delivery\DeliveryManager::class);
    }

    public function boot(): void {}
}