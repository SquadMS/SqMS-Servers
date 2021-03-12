<?php

namespace SquadMS\Servers\Models;

use SquadMS\Servers\Events\Internal\PlayerServerInfo\PlayerServerInfoSaving;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class PlayerServerInfo extends Model
{
    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saving' => PlayerServerInfoSaving::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'steam_id_64',
        'server_id',
        'bm_playtime',
        'playtime',
        'last_playing',
        'first_playing',
    ];

    /**
     * Get the related User.
     * 
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the related Server.
     * 
     * @return BelongsTo
     */
    public function server() : BelongsTo
    {
        return $this->belongsTo('App\Server');
    }

    /**
     * Accessor for the last_playing attribute, casts to carbon or null.
     *
     * @return Carbon|null
     */
    public function getLastPlayingAttribute() : ?Carbon
    {
        return Arr::has($this->attributes, 'last_playing') && Arr::get($this->attributes, 'last_playing') ? Carbon::parse(Arr::get($this->attributes, 'last_playing')) : null;
    }

    /**
     * Sets the last playing attrbite.
     *
     * @param [type] $value
     * @return void
     */
    public function setLastPlayingAttribute($value) : void
    {
        $this->attributes['last_playing'] = $value ? Carbon::parse($value) : null;
    }

    /**
     * Accessor for the first_playing attribute, casts to carbon or null.
     *
     * @return Carbon|null
     */
    public function getFirstPlayingAttribute() : ?Carbon
    {
        return Arr::has($this->attributes, 'first_playing') && Arr::get($this->attributes, 'first_playing') ? Carbon::parse(Arr::get($this->attributes, 'first_playing')) : null;
    }

    /**
     * Sets the first playing attrbite.
     *
     * @param [type] $value
     * @return void
     */
    public function setFirstPlayingAttribute($value) : void
    {
        $this->attributes['first_playing'] = $value ? Carbon::parse($value) : null;
    }
}
