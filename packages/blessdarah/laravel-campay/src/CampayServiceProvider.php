<?php

namespace BlessDarah\LaravelCampay;

use Illuminate\Support\ServiceProvider;

class CampayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-campay.php' => config_path('laravel-campay.php')
        ]);

        $this->app->singleton(LaravelCampay::class, function () {
            return new LaravelCampay();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/routes.php';
    }
}
