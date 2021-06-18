<?php

namespace SquadMS\Servers\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use SquadMS\Servers\Models\Ban;
use SquadMS\Servers\Models\ServerChatMessage;

trait SquadMSServersUserTrait
{
    /**
     * @inheritDoc
     */
    public function serverChatMessages(): HasMany
    {
        return $this->hasMany(ServerChatMessage::class);
    }

    /**
     * @inheritDoc
     */
    public function bans(): HasMany
    {
        return $this->hasMany(Ban::class);
    }

    /**
     * @inheritDoc
     */
    public function banned(): HasMany
    {
        return $this->hasMany(Ban::class, 'admin_id');
    }
}
