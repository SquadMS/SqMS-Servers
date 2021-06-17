<?php

namespace SquadMS\Servers\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use SquadMS\Servers\Models\ServerChatMessage;

trait SquadMSServersUserTrait {
    /**
     * @inheritDoc
     */
    public function serverChatMessages(): HasMany
    {
        return $this->hasMany(ServerChatMessage::class);
    }
}