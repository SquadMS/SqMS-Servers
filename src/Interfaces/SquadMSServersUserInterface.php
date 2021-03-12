<?php

namespace SquadMS\Servers\Interfaces;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SquadMS\Servers\Models\Server;

interface SquadMSServersUserInterface
{
    /**
     * Undocumented function
     *
     * @return HasMany
     */
    public function playerServerInfos() : HasMany;

    /**
     * Adds local playtime.
     * 
     * Notice: cant use updateOrCreate here since we have to check 
     * for last_playing in case first_playing is not set.
     *
     * @return void
     */
    public function addPlaytime(Server $server, int $playtime) : void;

    /**
     * Sets the BattleMetrics based playtime. Will reset the local
     * playtime as bm playtime is used as base to increment on.
     *
     * @return void
     */
    public function setBMPlaytime(Server $server, int $playtime) : void;

    /**
     * Increases the users seeding_points attribute
     * and also sets progress for the achievements.
     *
     * @return void
     */
    public function addSeedingPoints(int $seedingPoints = 1) : void;

    /**
     * Sets the first_playing attribute for the PlayerServerInfo if not already set.
     *
     * @return void
     */
    public function setFirstPlaying(Server $server, Carbon $when) : void;

    /**
     * Accessor for the Users playtime.
     * Computes from the PlayerServerInfos, will be cached.
     */
    public function getPlaytimeAttribute() : int;

    /**
     * Helper to clear the Playtime attribute cache.
     */
    public function clearPlaytimeCache() : void;

    public function getIsPlayingAttribute() : bool;

    public function setIsPlayingCache() : void;

    public function clearIsPlayingCache() : void;

    /**
     * Gets the Users last_playing attribute cached. It will
     * be computed from the related PlayerServerInfos.
     *
     * @return void
     */
    public function getLastPlayingAttribute() : ?Carbon;

    /**
     * Helper to clear the users last_playing cache.
     */
    public function clearLastPlayingCache() : void;

    /**
     * Accessor for the first_playing attribute.
     * Computes from the PlayerServerInfors, will be cached.
     */
    public function getFirstPlayingAttribute() : ?Carbon;

    /**
     * Helper to clear the users first_playing cache.
     */
    public function clearFirstPlayingCache() : void;
}