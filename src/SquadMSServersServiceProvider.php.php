<?php

namespace SquadMS\Servers;

use Illuminate\Support\ServiceProvider;

class SquadMSServersServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /* Migrations */
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
