<?php

namespace SquadMS\Servers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BanList extends Model
{
    /**
     * The Server in question.
     *
     * @return BelongsTo
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * The Ban-List in question.
     *
     * @return BelongsTo
     */
    public function banList(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }
}