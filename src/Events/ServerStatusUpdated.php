<?php

namespace SquadMS\Servers\Events;

use SquadMS\Servers\Helpers\MapHelper;
use SquadMS\Servers\Models\Server;
use DSG\SquadRCON\Data\Population;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;

class ServerStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $serverId;
    public $online;
    public $name;
    public $playerCount;
    public $slots;
    public $serverQueue;
    public $reservedSlots;
    public $currentMap;
    public $nextmap;
    public $population;
    public $connectURL;
    public $background;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Server $server)
    {
        $this->serverId = $server->id;

        $cached = $server->getFrontendCache();
        $this->online = Arr::get($cached, 'online');

        if ($this->online) {
            $this->name = Arr::get($cached, 'name');
            $this->playerCount = Arr::get($cached, 'playerCount');
            $this->slots = Arr::get($cached, 'slots');
            $this->serverQueue = Arr::get($cached, 'queue');
            $this->reservedSlots = Arr::get($cached, 'reservedSlots');
            $this->currentMap = Arr::get($cached, 'currentMap');
            $this->nextmap = Arr::get($cached, 'nextmap');
            $this->population = $this->getPopulation(Arr::get($cached, 'population'));
            $this->connectURL = Arr::get($cached, 'connectURL');
        }

        $this->background = MapHelper::getClassForMapName(Arr::get($cached, 'currentMap', ''));
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('server-status.' . $this->serverId);
    }

    private function getPopulation(?Population $population) : array 
    {
        $output = [];

        if (!is_null($population)) {
            foreach ($population->getTeams() as $team) {
                $t = [
                    'class' => \App\Helpers\FactionHelper::getClassNameForFaction($team->getName()),
                    'name' => $team->getName(),
                    'squads' => [],
                    'players' => [],
                ];

                if (count($team->getSquads())) foreach ($team->getSquads() as $squad) {
                    $s = [
                        'id' => $squad->getId(),
                        'name' => $squad->getName(),
                        'players' => [],
                    ];

                    foreach ($squad->getPlayers() as $player) {
                        $p = [
                            'url' => route('profile', $player->getSteamId()),
                            'name' => $player->getName(),
                        ];

                        $s['players'][] = $p;
                    }

                    $t['squads'][] = $s;
                }

                foreach ($team->getPlayers() as $player) {
                    $p = [
                        'url' => route('profile', $player->getSteamId()),
                        'name' => $player->getName(),
                    ];

                    $t['players'][] = $p;
                }

                $output[] = $t;
            }
        }

        return $output;
    }
}