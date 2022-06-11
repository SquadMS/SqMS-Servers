<?php

namespace SquadMS\Servers\Providers;

use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;
use SquadMS\Servers\Filament\Resources\ServerResource;

class FilamentServiceProvider extends PluginServiceProvider
{
    protected array $resources = [
        ServerResource::class,
    ];

    public function configurePackage(Package $package): void
    {
        $package->name('sqms-servers');
    }
}
