<?php

use Illuminate\Support\Facades\Route;
use SquadMS\Servers\Http\Controllers\APIServersController;

Route::group([
    'prefix'     => 'api',
    'middleware' => 'api',
], function () {
    /* It is important to use the sqms-worker-auth Middleware here to prevent unauthorized access! */
    Route::group(['prefix' => 'rcon-worker', 'as' => 'worker.', 'middleware' => ['api', 'sqms-worker-auth']], function () {
        /* Get Server (connection) info */
        Route::get('servers', [APIServersController::class, 'servers']);

        /* Event-Webhooks */
        Route::post('chat-message', [APIServersController::class, 'chatMessage']);
    });
});
