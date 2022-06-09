<?php

namespace SquadMS\Servers\Contracts;

use Carbon\Carbon;
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

    /**
     * How long the user is currently banned for.
     * null   = Not banned at all
     * true   = Banned forever
     * Carbon = Banned until X.
     */
    public function getBannedUntilAttribute(): null|bool|Carbon;
}
