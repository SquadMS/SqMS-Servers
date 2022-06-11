<?php

namespace SquadMS\Servers\Providers;

use Filament\PluginServiceProvider;
 
class FilamentServiceProvider extends PluginServiceProvider
{
    protected array $resources = [
        ServerResource::class,
    ];
}
