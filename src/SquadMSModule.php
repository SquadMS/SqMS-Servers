<?php

namespace SquadMS\Servers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use SquadMS\Foundation\Facades\SquadMSMenu;
use SquadMS\Foundation\Helpers\NavigationHelper;
use SquadMS\Foundation\Menu\SquadMSMenuEntry;
use SquadMS\Foundation\Modularity\Contracts\SquadMSModule as SquadMSModuleContract;

class SquadMSModule extends SquadMSModuleContract
{
    public static function getIdentifier(): string
    {
        return 'sqms-servers';
    }

    public static function getName(): string
    {
        return 'SquadMS Servers';
    }

    public static function publishAssets(): void
    {
        Artisan::call('vendor:publish', [
            '--tag'      => self::getIdentifier().'-assets',
            '--force'    => true,
        ]);
    }

    public static function registerMenuEntries(string $menu): void
    {
        switch ($menu) {
            case 'main':
                SquadMSMenu::register(
                    'main',
                    (new SquadMSMenuEntry(Config::get('sqms-servers.routes.def.servers.name'), fn () => Lang::get('sqms-servers::navigation.servers'), true))
                    ->setActive(fn (SquadMSMenuEntry $link) => NavigationHelper::isCurrentRoute(Config::get('sqms-servers.routes.def.servers.name')))
                    ->setOrder(200)
                );

                break;
        }
    }
}
