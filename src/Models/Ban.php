<?php

namespace SquadMS\Servers\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

class Ban extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reason',

        'description',

        'start',
        'end',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start' => 'datetime',
    ];

    /**
     * Custom Accessor for the end time. This is required as end is
     * nullable and Carbon does parse null to valid dates (instead of null).
     *
     * @return Carbon|null
     */
    public function getEndAttribute(): ?Carbon
    {
        $original = Arr::has($this->attributes, 'end');

        if ($original) {
            return  Carbon::parse($original);
        }

        return null;
    }

    /**
     * Helper to set the end attribute. Does only accept a 
     * valod Carbon instance or null (for permanent).
     *
     * @param Carbon|null $value
     * @return void
     */
    public function setEndAttribute(?Carbon $value): void
    {
        Arr::set($this->attributes, 'end', $value);
    }

    /**
     * The User who has created the Ban.
     *
     * @return BelongsTo
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Config::get('sqms.user.model'), 'admin_id');
    }

    /**
     * The User who has been banned.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Config::get('sqms.user.model'));
    }

    /**
     * The ban-List that this ban has been created on.
     *
     * @return BelongsTo
     */
    public function banList(): BelongsTo
    {
        return $this->belongsTo(BanList::class);
    }
}