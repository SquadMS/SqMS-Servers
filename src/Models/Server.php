<?php

namespace SquadMS\Servers\Models;

use SquadMS\Servers\Data\QueryResult;
use SquadMS\Servers\Events\Internal\Server\ServerSaved;
use SquadMS\Servers\Events\Internal\Server\ServerSaving;
use SquadMS\Servers\Events\ServerStatusUpdated;
use SquadMS\Servers\Services\ServerQueryService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use DSG\SquadRCON\Data\ServerConnectionInfo;
use DSG\SquadRCON\SquadServer as RCON;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Server extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'host',
        'port',
        'enable_query',
        'query_port',
        'rcon_port',
        'rcon_password',
        'main',
        'battlemetrics_id',
        'reserved_playtime',
        'announce_seeding',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saving' => ServerSaving::class,
        'saved' => ServerSaved::class,
    ];

    /**
     * Undocumented function
     *
     * @return HasMany
     */
    public function playerServerInfos() : HasMany
    {
        return $this->hasMany('SquadMS\Servers\PlayerServerInfo');
    }

     /**
     * Undocumented function
     *
     * @return HasMany
     */
    public function serverCommands() : HasMany
    {
        return $this->hasMany('SquadMS\Servers\ServerCommand');
    }

    public function getConnectUriAttribute() : string
    {
        return 'steam://connect/' . $this->host . ':' . $this->port . '/';
    }

    /**
     * Retrieves and returns the servers empty since timestamp.
     * If there is no timestamp cached it will return null
     *
     * @return Carbon|null
     */
    public function getHasRconDataAttribute()
    {
        return $this->rcon_port !== null && $this->rcon_password !== null;
    }
    
    public function getGameQData() : array
    {
        return [
            'type' => 'squad',
            'host' => $this->host . ':' . $this->port,
        ];
    }

    /**
     * Initializes and returns a new RCON connection.
     * 
     * @throws \Throwable
     */
    public function getRconConnection() : ?RCON
    {
        if ($this->has_rcon_data) {
            return new RCON(new ServerConnectionInfo($this->host, $this->rcon_port, $this->rcon_password));
        } else {
            return null;
        }
    }

    /**
     * Generates an RGB color array
     * from the model ID.
     *
     * @return array
     */
    public function getColorAttribute() : array
    {
        $hash = md5('color' . $this->id);
        return [
            hexdec(substr($hash, 0, 2)), // R
            hexdec(substr($hash, 2, 2)), // G
            hexdec(substr($hash, 4, 2)), // B
        ];
    }

    /**
     * Retrieves and returns the servers empty since timestamp.
     * If there is no timestamp cached it will return null
     */
    public function getEmptySinceAttribute() : ?Carbon
    {
        $cache = Cache::get($this->getCacheKey('serverEmptySince'));
        /* Get empty since cache */
        if ($cache) {
            /* Get and parse empty since timestamp */
            return Carbon::parse($cache);
        } else {
            return null;
        }
    }

    /**
     * Retrieves and returns the servers empty since timestamp.
     * If there is no timestamp cached it will return null
     *
     * @param Carbon $value
     * @return void
     */
    public function setEmptySinceAttribute(Carbon $value) : void
    {        
        /* Get empty since cache */
        if (!Cache::get($this->getCacheKey('serverEmptySince'))) {
            Cache::forever($this->getCacheKey('serverEmptySince'), $value->toDateTimeString());
        }
    }

    /**
     * Determines if the server is online by using
     * the frontend cache.
     *
     * @return bool
     */
    public function getOnlineAttribute() : bool
    {
        $cache = $this->getFrontendCache();
        return $cache && Arr::get($cache, 'online', false);
    }

    public function hasPlayer(string $steamId64) : bool
    {
        $cache = $this->getFrontendCache();

        if ($cache) {
            /** @var \DSG\SquadRCON\Data\Population|null */
            if (($population = Arr::get($cache, 'population'))) {
                return !!$population->getPlayerBySteamId($steamId64);
            }
        }
        return false;
    }

    /**
     * Retrieves and returns the servers empty since timestamp.
     * If there is no timestamp cached it will return null
     *
     * @return void
     */
    public function forgetEmptySince() : void
    {
        Cache::forget($this->getCacheKey('serverEmptySince'));
    }

    public function createFrontendCache(QueryResult $result) : void
    {
        if ($result->online()) {
            $data = [
                'updated' => Carbon::now()->toDateTimeString(),
                'online' => $result->online(),
    
                'name' => $result->name(),
                'playerCount' => $result->count(),
                'slots' => $result->slots(),
                'queue' => $result->queue(),
                'reservedSlots' => $result->reserved(),
    
                'currentMap' => $result->map(),
                'nextmap' => $result->nextMap(),
    
                'population' => $result->population(),
    
                'connectURL' => $result->connectionURI(),
            ];
    
            /* Cache result */
            Cache::forever($this->getCacheKey('serverQuery'), $data);
        } else {
            /* Try to get the old cache */
            $oldCache = Cache::get($this->getCacheKey('serverQuery'));

            /* check if there is no cache or if there is if it is older than 5 minutes */
            if ( !is_array($oldCache) || !isset($oldCache['updated']) || Carbon::parse($oldCache['updated'])->lessThan(Carbon::now()->subMinutes(5)) ) {
                /* Cache as offline result */
                Cache::put($this->getCacheKey('serverQuery'), [
                    'updated' => Carbon::now()->toDateTimeString(),
                    'online' => $result->online(),
                ], 60 * 5);
            }
        }

        event(new ServerStatusUpdated($this));
    }

    public function getFrontendCache() : array
    {
        return Cache::get($this->getCacheKey('serverQuery'), [
            'online' => false
        ]);
    }

    public function forgetFrontendCache() : bool
    {
        return Cache::forget($this->getCacheKey('serverQuery'));
    }

    public function savePlaytimes() : void
    {
        ServerQueryService::addPlaytime(new Collection(), $this);
    }

    /**
     * Helper method to create an unique cache key for this server.
     *
     * @return string
     */
    private function getCacheKey(string $identifier) : string 
    {
        return $identifier . $this->host . ':' . $this->port;
    }
}