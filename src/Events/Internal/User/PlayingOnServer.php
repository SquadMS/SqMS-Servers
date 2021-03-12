<?php

namespace SquadMS\Servers\Events\Internal\User;

use SquadMS\Servers\Models\Server;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayingOnServer
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public Server $server;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, Server $server)
    {
        $this->user = $user;
        $this->server = $server;
    }
}