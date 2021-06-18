<?php

namespace SquadMS\Servers\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        /* Middlewares */
        Route::aliasMiddleware('sqms-worker-auth', \SquadMS\Servers\Http\Middleware\WorkerAuth::class);

        /* Routes */
        $routesPath = __DIR__.'/../../routes';

        /* WEB routes */
        Route::group([
            'prefix'     => Config::get('sqms-servers.routes.prefix'),
            'middleware' => Config::get('sqms-servers.routes.middleware'),
        ], function () use ($routesPath) {
            $this->loadRoutesFrom($routesPath.'/web.php');
        });

        /* API routes */
        Route::group([
            'prefix'     => 'api',
            'middleware' => 'api',
        ], function () use ($routesPath) {
            $this->loadRoutesFrom($routesPath.'/api.php');
        });
    }
}
