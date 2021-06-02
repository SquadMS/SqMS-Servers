<?php

namespace SquadMS\Servers\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            /* Fetch unfetched or outdated users */
            $schedule->job(new FetchUsers())->withoutOverlapping()->everyFiveMinutes();
        });
    }
}