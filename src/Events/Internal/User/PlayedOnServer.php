<?php

namespace SquadMS\Servers\Events\Internal\User;

use SquadMS\Servers\Models\Server;
use Carbon\Carbon;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayedOnServer
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public Server $server;

    public Carbon $joined;

    public int $playtime;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, Server $server, Carbon $joined, int $playtime)
    {
        $this->user = $user;
        $this->server = $server;
        
        $this->joined = $joined;

        $this->playtime = $playtime;
    }
}