<?php

namespace SquadMS\Servers\Providers;

use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require base_path(__DIR__.'/../../routes/channels.php');
    }
}
