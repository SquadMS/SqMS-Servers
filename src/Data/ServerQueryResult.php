<?php

namespace SquadMS\Servers\Data;

use Carbon\Carbon;
use DSG\SquadRCON\Data\Population;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use SquadMS\Servers\Events\ServerStatusUpdated;
use SquadMS\Servers\Models\Server;
use SquadMS\Servers\Repositories\ServerRepositoriy;

class ServerQueryResult {
    private ?Server $server = null;

    private ?Carbon $created = null;

    private bool $online = false;

    private ?string $name;

    private int $slots = 0;

    private int $reserved = 0;

    private int $count = 0;

    private int $queue = 0;

    private ?Population $population = null;

    private ?string $level = null;

    private ?string $layer = null;

    private ?string $nextLevel = null;

    private ?string $nextLayer = null;

    function __construct(Server $server, ?Carbon $created = null) {
        $this->server = $server;
        $this->created = $created ?? Carbon::now();
    }

    /**
     * Method to set data that can be obtained by a simple A2S query.
     *
     * @param boolean $online
     * @param string $name
     * @param integer $slots
     * @param integer $reserved
     * @param integer $count
     * @param integer $queue
     * @return void
     */
    public function setQueryData(bool $online = false, string $name = '', int $slots = 0, int $reserved = 0, int $count = 0, int $queue = 0) : void
    {
        $this->online = $online;
        $this->name = $name;
        $this->slots = $slots;
        $this->reserved = $reserved;
        $this->queue = $queue;
        $this->count = $count; 
    }

    /**
     * Method to set data that can only be obtained by RCON.
     *
     * @param array $currentMapInfo
     * @param array $nextMapInfo
     * @param Population $population
     * @return void
     */
    public function setRCONData(array $currentMapInfo, array $nextMapInfo, Population $population) : void
    {
        $this->level = Arr::get($currentMapInfo, 'level');
        $this->layer = Arr::get($currentMapInfo, 'layer');
        $this->nextLevel = Arr::get($nextMapInfo, 'level');
        $this->nextLayer = Arr::get($nextMapInfo, 'layer');
        $this->population = $population;
        $this->count = count($this->population->getPlayers());  
    }

    public function __serialize(): array
    {
        /* Serialize the data, use related server id instead of searializing the model */
        return [
            'server'     => $this->server->id,
            'created'    => $this->created,
            'online'     => $this->online,
            'name'       => $this->name,
            'slots'      => $this->slots,
            'reserved'   => $this->reserved,
            'count'      => $this->count,
            'queue'      => $this->queue,
            'population' => $this->population,
            'level'      => $this->level,
            'layer'      => $this->layer,
            'nextLevel'  => $this->level,
            'nextLayer'  => $this->level,
        ];
    }

    public function __unserialize(array $data): void
    {
        /* Find related Server or fail */
        $this->server = ServerRepositoriy::getServerModelQuery()->findOrFail(Arr::get($data, 'server', -1));

        /* Rebuild query data and preserve class defaults */
        $this->created    = Arr::get($data, 'created', Carbon::now());
        $this->online     = Arr::get($data, 'online', $this->online);
        $this->name       = Arr::get($data, 'name', $this->server->name);
        $this->slots      = Arr::get($data, 'slots', $this->slots);
        $this->reserved   = Arr::get($data, 'reserved', $this->reserved);
        $this->count      = Arr::get($data, 'count', $this->count);
        $this->queue      = Arr::get($data, 'queue', $this->queue);
        $this->population = Arr::get($data, 'population', $this->population);
        $this->level      = Arr::get($data, 'level', $this->level);
        $this->layer      = Arr::get($data, 'layer', $this->layer);
        $this->nextLevel  = Arr::get($data, 'nextLevel', $this->nextLevel);
        $this->nextLayer  = Arr::get($data, 'nextLayer', $this->nextLayer);
    }

    public function save() : void
    {
        Cache::forever('sqms-servers-query-result::' . $this->server->id, $this);

        ServerStatusUpdated::dispatch($this);
    }

    public static function load(Server $server) : self
    {
        return Cache::get('sqms-servers-query-result::' . $server->id, new ServerQueryResult($server));
    }

    /* Property-Accessors */

    public function server() : Server
    {
        return $this->server;
    }

    public function created() : Carbon
    {
        return $this->created;
    }

    public function online() : bool
    {
        return $this->online;
    }

    public function name() : string
    {
        return $this->name ?? $this->server->name;
    }

    public function slots() : int
    {
        return $this->slots;
    }

    public function reserved() : int
    {
        return $this->reserved;
    }

    public function count() : int
    {
        return $this->count;
    }

    public function queue() : int
    {
        return $this->queue;
    }

    public function population() : ?Population
    {
        return $this->population;
    }

    public function level() : ?string
    {
        return $this->level;
    }

    public function layer() : ?string
    {
        return $this->layer;
    }

    public function nextLevel() : ?string
    {
        return $this->nextLevel;
    }

    public function nextLayer() : ?string
    {
        return $this->nextLayer;
    }

    public function connectionURI() : string
    {
        return $this->server->connect_url;
    }

    public function steamIds() : array
    {
        /* Process player List and get steamIds */
        $steamIds = [];

        if (!is_null($this->population)) {
            foreach ($this->population->getPlayers() as $player) {
                $steamIds[] = $player->getSteamId();
            }
        }

        return $steamIds;
    }
}