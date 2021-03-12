<?php

namespace SquadMS\Servers\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use SquadMS\Servers\Events\Internal\User\AddedSeedingPoints;
use SquadMS\Servers\Events\Internal\User\UpdatedPlaytime;
use SquadMS\Servers\Models\PlayerServerInfo;
use SquadMS\Servers\Models\Server;

trait SquadMSServersUserTrait
{
    /**
     * @inheritDoc
     */
    public function playerServerInfos() : HasMany
    {
        return $this->hasMany(PlayerServerInfo::class);
    }

    /**
     * Adds local playtime.
     * 
     * Notice: cant use updateOrCreate here since we have to check 
     * for last_playing in case first_playing is not set.
     *
     * @return void
     */
    public function addPlaytime(Server $server, int $playtime) : void
    {
        /* Update or create the player server info and increase the playtime */
        PlayerServerInfo::updateOrCreate([
            'user_id'   => $this->id,
            'server_id' => $server->id,
        ], [
            'playtime'  => DB::raw('playtime + ' . $playtime),
        ]);

        event(new UpdatedPlaytime($this));
    }

    /**
     * Sets the BattleMetrics based playtime. Will reset the local
     * playtime as bm playtime is used as base to increment on.
     *
     * @return void
     */
    public function setBMPlaytime(Server $server, int $playtime) : void
    {
        PlayerServerInfo::updateOrCreate([
            'server_id' => $server->id,
            'user_id'   => $this->id,
        ], [
            'bm_playtime'  => $playtime,
            'playtime'     => 0
        ]);

        event(new UpdatedPlaytime($this));
    }

    /**
     * Increases the users seeding_points attribute
     * and also sets progress for the achievements.
     *
     * @return void
     */
    public function addSeedingPoints(int $seedingPoints = 1) : void
    {
        $this->seeding_points += $seedingPoints;
        $this->save();

        event(new AddedSeedingPoints($this, $seedingPoints));
    }

    /**
     * @inheritDoc
     */
    public function setFirstPlaying(Server $server, Carbon $when) : void
    {
        /* Get the associated info or instanciate a new one */
        $info = PlayerServerInfo::firstOrNew([
            'user_id'   => $this->id,
            'server_id' => $server->id,
        ], [
            'first_playing' => $when,
        ]);

        /* Update the first_playing timestamp */
        $info->first_playing = $info->first_playing ?? $when;
        
        /* weite the info in the database */
        $info->save();
    }

    /**
     * @inheritDoc
     */
    public function getPlaytimeAttribute() : int
    {
        return Cache::tags(['user-playtime', 'user-' . $this->id])->rememberForever('user-playtime-' . $this->id, function() {
            $result = $this->playerServerInfos()->whereHas('server', function ($query) {
                $query->where('reserved_playtime', true);
            })->select(DB::raw('SUM(bm_playtime + playtime) as total_time'))->value('total_time') ?? 0;

            return intval($result);
        });
    }

    /**
     * @inheritDoc
     */
    public function clearPlaytimeCache() : void
    {
        Cache::tags(['user-playtime', 'user-' . $this->id])->forget('user-playtime-' . $this->id);
    }

    /**
     * @inheritDoc
     */
    public function getIsPlayingAttribute() : bool
    {
        return Cache::tags('users-is-playing')->has('user-is-playing-' . $this->id);
    }

    /**
     * @inheritDoc
     */
    public function setIsPlayingCache() : void
    {
        Cache::tags('users-is-playing')->set('user-is-playing-' . $this->id, true, 120);
    }

    /**
     * @inheritDoc
     */
    public function clearIsPlayingCache() : void
    {
        Cache::tags('users-is-playing')->forget('user-is-playing-' . $this->id);
    }

    /**
     * @inheritDoc
     */
    public function getLastPlayingAttribute() : ?Carbon
    {
        $value = Cache::tags(['user-lastplaying', 'user-' . $this->id])->rememberForever('user-lastplaying-' . $this->id, function() {
            $info = $this->playerServerInfos()->orderByDesc('last_playing')->first();

            return $info && $info->last_playing ? $info->last_playing->toDateTimeString() : false;
        });

        return $value ? Carbon::parse($value) : null;
    }

    /**
     * @inheritDoc
     */
    public function clearLastPlayingCache() : void
    {
        Cache::tags(['user-lastplaying', 'user-' . $this->id])->forget('user-lastplaying-' . $this->id);
    }

    /**
     * @inheritDoc
     */
    public function getFirstPlayingAttribute() : ?Carbon
    {
        $value = Cache::tags(['user-firstplaying', 'user-' . $this->id])->rememberForever('user-firstplaying-' . $this->id, function() {
            $when = false;

            foreach ($this->playerServerInfos as $info) {
                /* Should not really happen but fix errornous entries */
                if (is_null($info->first_playing) && $info->last_playing) {
                    $info->first_playing = $info->last_playing;
                    $info->save();
                }

                if ($when === false || ($info->first_playing && $info->first_playing->lessThan($when))) {
                    $when = $info->first_playing;
                }
            }

            return $when ? $when->toDateTimeString() : false;
        });

        return $value ? Carbon::parse($value) : null;
    }

    /**
     * @inheritDoc
     */
    public function clearFirstPlayingCache() : void
    {
        Cache::tags(['user-firstplaying', 'user-' . $this->id])->forget('user-firstplaying-' . $this->id);
    }
}