<?php

namespace SquadMS\Servers\Data;

use DSG\SquadRCON\Data\Population;
use Illuminate\Support\Arr;
use SquadMS\Servers\Models\Server;

class ServerQueryResult {
    private Server $server;

    private bool $online = false;

    private string $name;

    private int $slots = 0;

    private int $reserved = 0;

    private int $count = 0;

    private int $queue = 0;

    private ?Population $population = null;

    private ?string $level = null;

    private ?string $layer = null;

    private ?string $nextLevel = null;

    private ?string $nextLayer = null;

    function __construct(Server $server, bool $online = false, string $name = '', int $slots = 0, int $reserved = 0, int $count = 0, int $queue = 0) {
        $this->server = $server;
        $this->online = $online;
        $this->name = $name;
        $this->slots = $slots;
        $this->reserved = $reserved;
        $this->queue = $queue;
        $this->count = $count;      
    }

    public function setRCONData(array $currentMapInfo, array $nextMapInfo, Population $population) : void
    {
        $this->level = Arr::get($currentMapInfo, 'level');
        $this->layer = Arr::get($currentMapInfo, 'layer');
        $this->nextLevel = Arr::get($nextMapInfo, 'level');
        $this->nextLayer = Arr::get($nextMapInfo, 'layer');
        $this->population = $population;
        $this->count = count($this->population->getPlayers());  
    }

    public function server() : Server
    {
        return $this->server;
    }

    public function online() : bool
    {
        return $this->online;
    }

    public function name() : string
    {
        return $this->name;
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

    public function createFrontendCache() : void
    {
        $this->server->createFrontendCache($this);
    }
}