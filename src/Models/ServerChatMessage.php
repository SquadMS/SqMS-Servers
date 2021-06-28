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
        'server_id',
        'user_id',

        'type',

        'name',
        'content',

        'time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'time' => 'datetime',
    ];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s.u';

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => ServerChatMessageCreated::class,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'time_short',
        'type_formatted',
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

    /**
     * Accessor to get the short time as HH:SS.
     *
     * @return string
     */
    public function getTimeShortAttribute() : string
    {
        return $this->time->format('H:i');
    }

    /**
     * Accessor to get the formatted type.
     *
     * @return string
     */
    public function getTypeFormattedAttribute() : string
    {
        switch ($this->type) {
            case 'ChatAll':
                return 'All';
            
            case 'ChatTeam':
                return 'Team';

            case 'ChatSquad':
                return 'Squad';

            case 'ChatAdmin':
                return 'Admin';

            default:
                return $this->type;
        }
    }
}
