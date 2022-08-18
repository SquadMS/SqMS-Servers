<?php

namespace SquadMS\Servers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SquadMS\Foundation\Models\SquadMSUser;

class PlayerServerSession extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'joined_at',
        'left_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'joined_at' => 'timestamp',
        'left_at'   => 'timestamp',
    ];

    /**
     * The User who has been banned.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(SquadMSUser::class);
    }

    /**
     * The ban-List that this ban has been created on.
     *
     * @return BelongsTo
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }
}
