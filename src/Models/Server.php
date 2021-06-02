<?php

namespace SquadMS\Servers\Models;

use Illuminate\Database\Eloquent\Model;
use HiHaHo\EncryptableTrait\Encryptable;
use DSG\SquadRCON\SquadServer as RCON;
use DSG\SquadRCON\Data\ServerConnectionInfo;
use SquadMS\Servers\RCONCommandRunners\RCONWorkerCommandRunner;

class Server extends Model
{
    use Encryptable;

    protected $encryptable = [
        'rcon_password',
    ];

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

    public function getConnectUrlAttribute() : string
    {
        return 'steam://connect/' . $this->host . ':' . $this->game_port . '/';
    }

    public function getHasRconDataAttribute() : bool
    {
        return !is_null($this->rcon_port) && !is_null($this->rcon_password);
    }

    /**
     * Scope a query to only include servers that have RCON connection information available.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasRconInfo($query)
    {
        return $query->whereNotNull('rcon_port')->whereNotNull('rcon_password');
    }

    /**
     * Initializes and returns a new RCON connection.
     * 
     * @throws \Throwable
     */
    public function getRconConnection() : ?RCON
    {
        if ($this->has_rcon_data) {
            return new RCON(new ServerConnectionInfo($this->host, $this->rcon_port, $this->rcon_password), new RCONWorkerCommandRunner($this->id));
        } else {
            return null;
        }
    }
}