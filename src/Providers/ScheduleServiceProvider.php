<?php

namespace SquadMS\Servers\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use SquadMS\Servers\Jobs\QueryServer;
use SquadMS\Servers\Models\Server;

class ScheduleServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            foreach (Server::all() as $server) {
                $schedule->job(new QueryServer($server))->withoutOverlapping()->everyFiveMinutes();
            }
        });
    }
}
