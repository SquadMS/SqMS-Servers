<?php

namespace SquadMS\Servers\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;
use SquadMS\Servers\Data\ServerQueryResult;

class ServerQueried extends ShouldBeStored
{
    use Dispatchable;
    use SerializesModels;

    public ServerQueryResult $serverQueryResult;

    public function __construct(ServerQueryResult $serverQueryResult)
    {
        $this->serverQueryResult = $serverQueryResult;
    }
}
