<?php

namespace SquadMS\Servers\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface SquadMSServersUserInterface {
    /**
     * Get the messages that were sent by this user.
     */
    public function serverChatMessages(): HasMany;
}