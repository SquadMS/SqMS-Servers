<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use SquadMS\Foundation\Helpers\SquadMSRouteHelper;

Route::group([
    'prefix'     => Config::get('sqms-servers.routes.prefix'),
    'middleware' => Config::get('sqms-servers.routes.middleware'),
], function () {
    SquadMSRouteHelper::configurableRoutes(Config::get('sqms-servers.routes.def', []));
});

