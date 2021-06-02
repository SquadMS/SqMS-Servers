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

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'account_playtime' => false,
        'host'             => '127.0.0.1',
        'game_port'        => 7787,
        'query_port'       => 27165,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'rcon_port',
        'rcon_password',
    ];

    public function getConnectUrlAttribute() : string
    {
        return 'steam://connect/' . $this->host . ':' . $this->game_port . '/';
    }
}