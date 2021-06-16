<?php

namespace SquadMS\Servers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServerChatMessage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',

        'type',

        'name',
        'content',

        'time',
    ];

    /**
     * Get the Server this message was sent on.
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }
}