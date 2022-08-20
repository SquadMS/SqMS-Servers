<?php

namespace SquadMS\Servers\Projectors;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use SquadMS\Servers\Events\ServerQueried;
use SquadMS\Servers\Models\PlayerServerInfo;

class SeedtimeProjector extends Projector
{
    public function onServerQueried(ServerQueried $event): void
    {
        if ($event->serverQueryResult->count && $event->serverQueryResult->count >= $event->serverQueryResult->server->start_seeding && $event->serverQueryResult->count < $event->serverQueryResult->server->stop_seeding) {
            PlayerServerInfo::where('server_id', $event->serverQueryResult->server->id)
                ->whereHas('user', function (Builder $query) use ($event) {
                    $query->whereIn('steam_id_64', $event->serverQueryResult->steamIds());
                })
                ->update([
                    'seedtime' => DB::raw('seedtime + 1'),
                ]);
        }
    }
}
