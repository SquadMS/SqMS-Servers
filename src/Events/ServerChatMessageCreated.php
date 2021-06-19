<?php

namespace SquadMS\Servers\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use SquadMS\Servers\Models\ServerChatMessage;

class ServerChatMessageCreated implements ShouldBroadcast
{
    use Dispatchable;
    use SerializesModels;

    public ServerChatMessage $message;

    public function __construct(ServerChatMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]
     */
    public function broadcastOn()
    {
        return new PrivateChannel('server-chat');
    }
}
