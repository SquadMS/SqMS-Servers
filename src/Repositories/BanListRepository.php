<?php

namespace SquadMS\Servers\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use SquadMS\Servers\Models\BanList;

class BanListRepository
{
    /**
     * Returns a query builder for the configured user model.
     */
    public static function getModelQuery(): Builder
    {
        $class = Config::get('sqms-servers.models.banList', BanList::class);
        return call_user_func($class.'::query');
    }
}
