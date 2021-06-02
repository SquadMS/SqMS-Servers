<?php

namespace SquadMS\Servers\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use SquadMS\Foundation\Jobs\QueryServer;
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