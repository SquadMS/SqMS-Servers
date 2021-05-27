<?php

namespace SquadMS\Servers\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use SquadMS\Foundation\Facades\SquadMSRouter as FacadesSquadMSRouter;

class RouteServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        /* Routes */
        $routesPath = __DIR__ . '/../../routes';
        FacadesSquadMSRouter::define('sqms-servers', function () use ($routesPath) {
            Route::group([
                'prefix' => config('sqms-servers.routes.prefix'),
                'middleware' => config('sqms-servers.routes.middleware'),
            ], function () use ($routesPath) {
                $this->loadRoutesFrom($routesPath . '/web.php');
            });
        });
    }
}