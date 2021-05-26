<?php

namespace SquadMS\Servers\Providers;

use Illuminate\Support\ServiceProvider;
use SquadMS\Servers\SquadMSModule;
use SquadMS\Foundation\Facades\SquadMSModuleRegistry;

class ModulesServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        SquadMSModuleRegistry::register(SquadMSModule::class);
    }
}