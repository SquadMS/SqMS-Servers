<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use SquadMS\Foundation\Helpers\SquadMSRouteHelper;

Route::group([
    'prefix'     => Config::get('sqms-servers.routes.prefix'),
    'middleware' => Config::get('sqms-servers.routes.middleware'),
], function () {   
    SquadMSRouteHelper::localized(function () {
        Route::prefix('servers')->group(function () {
            Route::get('/', [\SquadMS\Servers\Http\Controllers\ServersController::class, 'index'])->name('servers');
            Route::get('{server}', [\SquadMS\Servers\Http\Livewire\Server::class, 'show'])->name('server');
        });
    });
});
