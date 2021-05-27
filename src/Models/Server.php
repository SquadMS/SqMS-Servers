<?php

namespace SquadMS\Servers\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',

        'account_playtime',

        'host',
        'game_port',
        'query_port',
        
        'rcon_port',
        'rcon_password',
    ];
}
