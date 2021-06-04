<?php

namespace SquadMS\Servers\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\Channel;
use SquadMS\Servers\Data\ServerQueryResult;

class ServerStatusUpdated
{
    use Dispatchable, SerializesModels, ShouldBroadcast;

    public ServerQueryResult $serverQueryResult;

    public function __construct(ServerQueryResult $serverQueryResult)
    {
        $this->serverQueryResult = $serverQueryResult;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]
     */
    public function broadcastOn()
    {
        return new Channel('server-status.' . $this->serverQueryResult->server()->id);
    }
}