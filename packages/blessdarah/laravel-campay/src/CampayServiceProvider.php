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
        //
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
