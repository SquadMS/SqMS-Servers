<?php

namespace SquadMS\Servers\Traits;

use Carbon\Carbon;
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

    /**
     * @inheritDoc
     */
    public function getBannedUntilAttribute(): null|bool|Carbon
    {
        /** @var null|true|Carbon Default is null / no ban */
        $bannedUntil = null;

        /* Check if we have an active Ban */
        foreach ($this->bans()->where('end', '>=', Carbon::now())->orWhereNull('end')->get() as $ban) {
            /* Check if the Ban has an end time or is permanent */
            if ($ban->end) {
                /* Check if the end date is less than the highest found end date, if so skip */
                if ($bannedUntil instanceof Carbon && $bannedUntil->lessThan($ban->end)) {
                    continue;
                }

                /* Set the highest found end time */
                $bannedUntil = $ban->end;
            } else {
                /* Set end time to true for a permanent Ban */
                $bannedUntil = true;

                /* Ban is permanent, skip checking other Bans */
                break;
            }
        }

        return $bannedUntil;
    }
}
