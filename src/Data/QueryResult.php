<?php

namespace SquadMS\Servers\Data;

use SquadMS\Servers\Models\Server;
use DSG\SquadRCON\Data\Population;
use DSG\SquadRCON\Team;
use DSG\SquadRCON\Player;

class QueryResult {
    /** @var Server The Server data object */
    private Server $server;

    /** @var bool The status of the Server */
    private bool $online = false;

    /** @var string The configured server name*/
    private string $name;

    /** @var int The configured number of slots (excluding reserved slots) */
    private int $slots = 0;

    /** @var int The configured amount of reserved slots */
    private int $reserved = 0;

    /** @var int The current player count (excluding queue) */
    private int $count = 0;

    /** @var int The current queue count (including reserved) */
    private int $queue = 0;

    /** @var null|\DSG\SquadRCON\Data\Population The current server population */
    private ?Population $population = null;

    /** @var \DSG\SquadRCON\Player[] The current server population */
    private array $playerList = [];

    /** @var string The current map*/
    private string $map;

    /** @var string The next map */
    private string $nextMap;

    /** @var string The connect URI */
    private string $connectURI;

    function __construct(Server $server, bool $online = false, string $name = '', int $slots = 0, int $reserved = 0, int $queue = 0, string $map = '', string $nextMap = '', ?Population $population = null, string $connectURI = null) {
        $this->server = $server;
        $this->online = $online;
        $this->name = $name;
        $this->slots = $slots;
        $this->reserved = $reserved;
        $this->queue = $queue;
        $this->map = $map;
        $this->nextMap = $nextMap;
        $this->connectURI = $connectURI ?? $this->server->connect_uri;
        $this->population = $population;
        $this->playerList = !is_null($this->population) ? $this->population->getPlayers() : [];
        $this->count = count($this->playerList);      
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

    public function map() : string
    {
        return $this->map;
    }

    public function nextMap() : string
    {
        return $this->nextMap;
    }

    public function connectionURI() : string
    {
        return $this->connectURI;
    }

    public function steamIds() : array
    {
        /* Process player List and get steamIds */
        $steamIds = [];

        foreach ($this->playerList as $player) {
            $steamIds[] = $player->getSteamId();
        }

        return $steamIds;
    }

    public function createFrontendCache() : void
    {
        $this->server->createFrontendCache($this);
    }
}