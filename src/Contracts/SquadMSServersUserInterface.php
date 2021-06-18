<?php

namespace SquadMS\Servers\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface SquadMSServersUserInterface
{
    /**
     * Get the messages that were sent by this user.
     */
    public function serverChatMessages(): HasMany;

    /**
     * The Bans that are related to this user.
     */
    public function bans(): HasMany;

    /**
     * The Bans this user has created.
     */
    public function banned(): HasMany;
}
