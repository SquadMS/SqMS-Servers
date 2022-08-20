<?php

namespace SquadMS\Servers\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use SquadMS\Foundation\Models\SquadMSUser;
use SquadMS\Servers\Models\Server;

class SquadMSUserRepository
{
    public static function playtime(SquadMSUser $user): int
    {
        return Cache::rememberForever('playtime'.$user->id, function () use ($user) {
            return $user->playerServerInfos()->sum('playtime') ?? 0;
        });
    }

    public static function eligiblePlaytime(SquadMSUser $user): int
    {
        return Cache::rememberForever('eligible-playtime'.$user->id, function () use ($user) {
            return $user->playerServerInfos()->whereHas('server', function (Builder $query) {
                $query->where('account_playtime', true);
            })->sum('playtime') ?? 0;
        });
    }

    public static function seedtime(SquadMSUser $user): int
    {
        return Cache::rememberForever('seedtime'.$user->id, function () use ($user) {
            return $user->playerServerInfos->sum('seedtime') ?? 0;
        });
    }

    public static function lastSeen(SquadMSUser $user, Server $server): ?Carbon
    {
        return Cache::rememberForever('last-seen-'.$user->id.'-'.$server->id, function () use ($user, $server) {
            /** @var \Illuminate\Database\Eloquent\Relations\HasMany */
            $relation = $user->playerServerSessions();

            /** @var ?\SquadMS\Servers\Models\PlayerServerSession */
            $playerServerSession = $relation->where('server_id', $server->id)
                ->orderBy('id')->first();

            if ($playerServerSession) {
                return $playerServerSession->last_seen ?? Carbon::now();
            }

            return null;
        });
    }
}
