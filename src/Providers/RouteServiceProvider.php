<?php

namespace SquadMS\Servers\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use SquadMS\Foundation\Facades\SquadMSAdminMenu as FacadesSquadMSAdminMenu;
use SquadMS\Foundation\Facades\SquadMSRouter as FacadesSquadMSRouter;
use SquadMS\Foundation\Facades\SquadMSMenu as FacadesSquadMSMenu;
use SquadMS\Foundation\Helpers\NavigationHelper;
use SquadMS\Foundation\Menu\SquadMSMenuEntry;

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

        /* Public Menu */
        FacadesSquadMSMenu::register(
            'main-left',
            (new SquadMSMenuEntry(Config::get('sqms-servers.routes.def.servers.name'), __('sqms-servers::navigation.servers'), true))
            ->setActive( fn (SquadMSMenuEntry $link) => NavigationHelper::isCurrentRoute(Config::get('sqms-servers.routes.def.servers.name')) )
            ->setOrder(200)
        );

        /* Admin Menu */
        FacadesSquadMSAdminMenu::register('admin-servers', 200);
        FacadesSquadMSMenu::prepend('admin-servers', fn () => view('sqms-foundation::components.navigation.heading', [
            'title'  => 'Server Management',
        ])->render());

        FacadesSquadMSMenu::register(
            'admin-servers',
            (new SquadMSMenuEntry(Config::get('sqms-servers.routes.def.admin-servers.name'), '<i class="bi bi-house-fill"></i> Servers', true))->setView('sqms-foundation::components.navigation.item')
            ->setActive( fn (SquadMSMenuEntry $link) => NavigationHelper::isCurrentRoute(Config::get('sqms-servers.routes.def.admin-servers.name')) )
            ->setOrder(200)
        );
    }
}