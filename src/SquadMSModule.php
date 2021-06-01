<?php

namespace SquadMS\Servers;

use Illuminate\Support\Facades\Config;
use SquadMS\Foundation\Facades\SquadMSAdminMenu;
use SquadMS\Foundation\Facades\SquadMSMenu;
use SquadMS\Foundation\Helpers\NavigationHelper;
use SquadMS\Foundation\Menu\SquadMSMenuEntry;
use SquadMS\Foundation\Modularity\Contracts\SquadMSModule as SquadMSModuleContract;

class SquadMSModule extends SquadMSModuleContract {
    static function getIdentifier() : string
    {
        return 'sqms-servers';
    }

    static function getName() : string
    {
        return 'SquadMS Servers';
    }

    static function publishAssets() : void
    {
        //
    }

    static function registerAdminMenus() : void
    {
        SquadMSAdminMenu::register('admin-servers', 200);
    }

    static function registerMenuEntries(string $menu) : void
    {
        switch ($menu) {
            case 'main-left':
                SquadMSMenu::register(
                    'main-left',
                    (new SquadMSMenuEntry(Config::get('sqms-servers.routes.def.servers.name'), fn () => __('sqms-servers::navigation.servers'), true))
                    ->setActive( fn (SquadMSMenuEntry $link) => NavigationHelper::isCurrentRoute(Config::get('sqms-servers.routes.def.servers.name')) )
                    ->setOrder(200)
                );

                break;

            case 'admin-servers':
                /* Admin Menu */
                SquadMSMenu::prepend('admin-servers', fn () => view('sqms-foundation::components.navigation.heading', [
                    'title'  => 'Server Management',
                ])->render());

                SquadMSMenu::register(
                    'admin-servers',
                    (new SquadMSMenuEntry(Config::get('sqms-servers.routes.def.admin-servers.name'), '<i class="bi bi-house-fill"></i> Servers', true))->setView('sqms-foundation::components.navigation.item')
                    ->setActive( fn (SquadMSMenuEntry $link) => NavigationHelper::isCurrentRoute(Config::get('sqms-servers.routes.def.admin-servers.name')) )
                    ->setOrder(200)
                );

                break;
        }
    }
}