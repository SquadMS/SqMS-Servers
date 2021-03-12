<?php

namespace SquadMS\Servers\Events\Internal\User;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddedSeedingPoints
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public int $addedSeedingPoints;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, int $addedSeedingPoints = 1)
    {
        $this->user = $user;
        $this->addedSeedingPoints = $addedSeedingPoints;
    }
}