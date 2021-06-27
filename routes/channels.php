<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Config;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('server-chat', function ($user) {
    return $user->can(Config::get('sqms-servers.permissions.module').' admin servers manage');
});