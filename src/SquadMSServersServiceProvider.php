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
        /* Configuration */
        $this->mergeConfigFrom(__DIR__.'/../config/sqms-servers.php', 'sqms-servers');

        /* Migrations */
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        /* Load Translations */
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'sqms-servers');
        
        /* Publish Assets */
        if ($this->app->runningInConsole()) {
            // Publish assets
            $this->publishes([
                __DIR__.'/../public' => public_path('themes/sqms-servers'),
            ], 'assets');
        }
    }
}
