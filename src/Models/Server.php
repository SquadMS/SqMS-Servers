<?php

namespace SquadMS\Servers\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use HiHaHo\EncryptableTrait\Encryptable;
use DSG\SquadRCON\SquadServer as RCON;
use DSG\SquadRCON\Data\ServerConnectionInfo;
use Illuminate\Support\Facades\Cache;
use SquadMS\Servers\Data\ServerQueryResult;
use SquadMS\Servers\RCONCommandRunners\RCONWorkerCommandRunner;

class Server extends Model
{
    use Encryptable;

    protected $encryptable = [
        'rcon_password',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',

        'account_playtime',

        'host',
        'game_port',
        'query_port',
        
        'rcon_port',
        'rcon_password',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'account_playtime' => false,
        'host'             => '127.0.0.1',
        'game_port'        => 7787,
        'query_port'       => 27165,
    ];

    public function getConnectUrlAttribute() : string
    {
        return 'steam://connect/' . $this->host . ':' . $this->game_port . '/';
    }

    public function getHasRconDataAttribute() : bool
    {
        return !is_null($this->rcon_port) && !is_null($this->rcon_password);
    }

    /**
     * Scope a query to only include servers that have RCON connection information available.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasRconData($query)
    {
        return $query->whereNotNull('rcon_port')->whereNotNull('rcon_password');
    }

    /**
     * Get the GameQ server connection data well formed.
     *
     * @return array
     */
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
            return new RCON(new ServerConnectionInfo($this->host, $this->rcon_port, $this->rcon_password), new RCONWorkerCommandRunner($this->id));
        } else {
            return null;
        }
    }

    public function createFrontendCache(ServerQueryResult $result) : void
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
    
                'level'     => $result->level(),
                'layer' => $result->layer(),
                'nextLevel' => $result->nextLevel(),
                'nextLayer'    => $result->nextLayer(),
    
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
    }

    public function getFrontendCache() : array
    {
        return Cache::get($this->getCacheKey('serverQuery'), [
            'online' => false
        ]);
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