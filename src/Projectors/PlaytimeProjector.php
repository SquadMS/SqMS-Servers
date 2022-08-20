<?php

namespace SquadMS\Servers\Projectors;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use SquadMS\Servers\Events\ServerQueried;
use SquadMS\Servers\Models\PlayerServerInfo;

class PlaytimeProjectors extends Projector
{
    public function onServerQueried(ServerQueried $event): void
    {
        if ($event->serverQueryResult->count) {
            PlayerServerInfo::where('server_id', $event->serverQueryResult->server->id)
                ->whereHas('user', function (Builder $query) use ($event) {
                    $query->whereIn('steam_id_64', $event->serverQueryResult->steamIds());
                })
                ->update([
                    'playtime' => DB::raw('playtime + 1'),
                ]);
        }
    }
}
