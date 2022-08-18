<?php

namespace SquadMS\Servers\Data;

use Carbon\Carbon;
use DSG\SquadRCON\Data\Population;
use Illuminate\Support\Arr;
use Spatie\DataTransferObject\DataTransferObject;
use SquadMS\Foundation\Facades\SDKDataReader;
use SquadMS\Foundation\Helpers\FactionHelper;
use SquadMS\Servers\Models\Server;

class ServerQueryResult extends DataTransferObject
{
    public Server $server;

    public string $name;

    public Carbon $created;

    public bool $online = false;

    public int $slots = 0;

    public int $reserved = 0;

    public int $count = 0;

    public int $publicQueue = 0;

    public int $reservedQueue = 0;

    public ?Population $population = null;

    public ?string $level = null;

    public ?string $layer = null;

    public ?string $nextLevel = null;

    public ?string $nextLayer = null;

    public function __construct(...$args)
    {
        Arr::set($args, 'created', Arr::get($args, 'created', Carbon::now()));

        parent::__construct(...$args);
    }

    public function queue(): int
    {
        return $this->publicQueue + $this->reservedQueue;
    }

    public function teamTags(): array
    {
        $tags = [];
        if ($this->layer) {
            foreach (range(1, 2) as $teamId) {
                $tags[$teamId] = FactionHelper::getFactionTag(SDKDataReader::getFactionForTeamID($this->layer, $teamId));
            }
        }

        return $tags;
    }

    public function steamIds(): array
    {
        /* Process player List and get steamIds */
        $steamIds = [];

        if (! is_null($this->population)) {
            foreach ($this->population->getPlayers() as $player) {
                $steamIds[] = $player->getSteamId();
            }
        }

        return $steamIds;
    }
}
