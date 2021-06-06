<?php

namespace SquadMS\Servers\Data;

use Carbon\Carbon;
use DSG\SquadRCON\Data\Population;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use SquadMS\Servers\Events\ServerStatusUpdated;
use SquadMS\Servers\Models\Server;
use SquadMS\Servers\Repositories\ServerRepositoriy;
use SquadMS\Foundation\Helpers\LevelHelper;

class ServerQueryResult {
    private ?Server $server = null;

    private ?Carbon $created = null;

    private bool $online = false;

    private ?string $name;

    private int $slots = 0;

    private int $reserved = 0;

    private int $count = 0;

    private int $publicQueue = 0;

    private int $reservedQueue = 0;

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
     * @param integer $publicQueue
     * @param integer $reservedQueue
     * @return void
     */
    public function setQueryData(bool $online = false, string $name = '', int $slots = 0, int $reserved = 0, int $count = 0, int $publicQueue = 0, int $reservedQueue = 0) : void
    {
        $this->online = $online;
        $this->name = $name;
        $this->slots = $slots;
        $this->reserved = $reserved;
        $this->publicQueue = $publicQueue;
        $this->reservedQueue = $reservedQueue;
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
        $this->nextLevel = Arr::get($nextMapInfo, 'nextLevel');
        $this->nextLayer = Arr::get($nextMapInfo, 'nextLayer');
        $this->population = $population;
        $this->count = count($this->population->getPlayers());  
    }

    public function toArray() : array
    {
        $primitivePopulation = [];
        if ($this->population()) foreach ($this->population()->getTeams() as $team) {
            $primitiveTeam = [
                'id'      => $team->getId(),
                'name'    => $team->getName(),
                'squads'  => [],
                'players' => [],
            ];

            /* Squads of this tTam */
            foreach ($team->getSquads() as $squad) {
                $primitiveSquad = [
                    'id'      => $squad->getId(),
                    'name'    => $squad->getName(),
                    'players' => [],
                ];

                /* Players of this Squad */
                foreach ($squad->getPlayers() as $player) {
                    $primitiveSquad['players'][] = [
                        'id'      => $player->getId(),
                        'steamId' => $player->getSteamId(),
                        'name'    => $player->getName(),
                    ];
                }

                $primitiveTeam['squads'][] = $primitiveSquad;
            }

            /* Unassigned players */
            foreach ($team->getPlayers() as $player) {
                $primitiveTeam['players'][] = [
                    'id'      => $player->getId(),
                    'steamId' => $player->getSteamId(),
                    'name'    => $player->getName(),
                ];
            }

            $primitivePopulation[] = $primitiveTeam;
        }

        return [
            'server'        => $this->server->id,
            'created'       => $this->created,
            'online'        => $this->online,
            'name'          => $this->name,
            'slots'         => $this->slots,
            'reserved'      => $this->reserved,
            'count'         => $this->count,
            'publicQueue'   => $this->publicQueue,
            'reservedQueue' => $this->reservedQueue,
            'queue'         => $this->queue(),
            'population'    => $primitivePopulation,
            'level'         => $this->level,
            'layer'         => $this->layer,
            'nextLevel'     => $this->nextLevel,
            'nextLayer'     => $this->nextLayer,
            'levelClass'    => LevelHelper::levelToClass($this->level)
        ];
    }

    public function __serialize(): array
    {
        /* Serialize the data, use related server id instead of searializing the model */
        return [
            'server'        => $this->server->id,
            'created'       => $this->created,
            'online'        => $this->online,
            'name'          => $this->name,
            'slots'         => $this->slots,
            'reserved'      => $this->reserved,
            'count'         => $this->count,
            'publicQueue'   => $this->publicQueue,
            'reservedQueue' => $this->reservedQueue,
            'population'    => $this->population,
            'level'         => $this->level,
            'layer'         => $this->layer,
            'nextLevel'     => $this->nextLevel,
            'nextLayer'     => $this->nextLayer,
        ];
    }

    public function __unserialize(array $data): void
    {
        /* Find related Server or fail */
        $this->server = ServerRepositoriy::getServerModelQuery()->findOrFail(Arr::get($data, 'server', -1));

        /* Rebuild query data and preserve class defaults */
        $this->created       = Arr::get($data, 'created', Carbon::now());
        $this->online        = Arr::get($data, 'online', $this->online);
        $this->name          = Arr::get($data, 'name', $this->server->name);
        $this->slots         = Arr::get($data, 'slots', $this->slots);
        $this->reserved      = Arr::get($data, 'reserved', $this->reserved);
        $this->count         = Arr::get($data, 'count', $this->count);
        $this->publicQueue   = Arr::get($data, 'publicQueue', $this->publicQueue);
        $this->reservedQueue = Arr::get($data, 'reservedQueue', $this->reservedQueue);
        $this->population    = Arr::get($data, 'population', $this->population);
        $this->level         = Arr::get($data, 'level', $this->level);
        $this->layer         = Arr::get($data, 'layer', $this->layer);
        $this->nextLevel     = Arr::get($data, 'nextLevel', $this->nextLevel);
        $this->nextLayer     = Arr::get($data, 'nextLayer', $this->nextLayer);
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

    public function publicQueue() : int
    {
        return $this->publicQueue;
    }

    public function reservedQueue() : int
    {
        return $this->reservedQueue;
    }

    public function queue() : int
    {
        return $this->publicQueue + $this->reservedQueue;
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