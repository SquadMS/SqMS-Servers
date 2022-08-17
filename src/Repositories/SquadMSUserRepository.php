<?php

namespace SquadMS\Servers\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use SquadMS\Foundation\Models\SquadMSUser;

class SquadMSUserRepository
{
    public static function playtime(SquadMSUser $user): int
    {
        return Cache::rememberForever('playtime' . $user->id, function () use ($user) {
            return $user->playerServerInfos()->sum('playtime') ?? 0;
        });
    }

    public static function eligiblePlaytime(SquadMSUser $user): int
    {
        return Cache::rememberForever('eligible-playtime' . $user->id, function () use ($user) {
            return $user->playerServerInfos()->whereHas('server', function (Builder $query) {
                $query->where('account_playtime', true);
            })->sum('playtime') ?? 0;
        });
    }

    public static function seedtime(SquadMSUser $user): int
    {
        return Cache::rememberForever('seedtime' . $user->id, function () use ($user) {
            return $user->playerServerInfos->sum('seedtime') ?? 0;
        });
    } 
}
