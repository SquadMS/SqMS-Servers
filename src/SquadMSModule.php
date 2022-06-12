<?php

namespace SquadMS\Servers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use SquadMS\Foundation\Facades\SquadMSMenu;
use SquadMS\Foundation\Helpers\NavigationHelper;
use SquadMS\Foundation\Menu\SquadMSMenuEntry;
use SquadMS\Foundation\Modularity\Contracts\SquadMSModule as SquadMSModuleContract;
use SquadMS\Servers\Jobs\QueryServer;
use SquadMS\Servers\Models\Server;

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

    public static function registerMenuEntries(string $menu): void
    {
        switch ($menu) {
            case 'main-left':
                SquadMSMenu::register(
                    'main-left',
                    (new SquadMSMenuEntry(Config::get('sqms-servers.routes.def.servers.name'), fn () => Lang::get('sqms-servers::navigation.servers'), true))
                    ->setActive(fn (SquadMSMenuEntry $link) => NavigationHelper::isCurrentRoute(Config::get('sqms-servers.routes.def.servers.name')))
                    ->setOrder(200)
                );

                break;
        }
    }

    public static function schedule(Schedule $schedule): void
    {
        foreach (Server::all() as $server) {
            $schedule->job(new QueryServer($server))->withoutOverlapping()->everyMinute();
        }
    }
}
