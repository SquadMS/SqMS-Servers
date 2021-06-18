<?php

namespace SquadMS\Servers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class BanList extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',

        'global',
    ];

    /**
     * The Servers that this Ban-List does affect.
     * If there are none it is applied globally.
     *
     * @return BelongsTo
     */
    public function servers(): HasManyThrough
    {
        return $this->hasManyThrough(Server::class, ServerBanList::class);
    }
}
