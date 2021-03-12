<?php

namespace SquadMS\Servers;

class ServersServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /* Register Routes */
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}