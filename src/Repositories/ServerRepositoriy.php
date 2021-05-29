<?php

namespace SquadMS\Servers\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class ServerRepositoriy
{
    /**
     * Returns a query builder for the configured user model.
     */
    public static function getServerModelQuery() : Builder {
        $class = Config::get('sqms-servers.models.server');
        return call_user_func($class . '::query');
    }
}