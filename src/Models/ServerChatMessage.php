<?php

namespace SquadMS\Servers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Config;
use SquadMS\Servers\Events\ServerChatMessageCreated;

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
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => ServerChatMessageCreated::class,
    ];

    /**
     * Get the User that sent this message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Config::get('sqms.user.model'));
    }

    /**
     * Get the Server this message was sent on.
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }
}
