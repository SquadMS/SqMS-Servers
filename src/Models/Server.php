<?php

namespace SquadMS\Servers\Models;

use DSG\SquadRCON\Data\ServerConnectionInfo;
use DSG\SquadRCON\SquadServer as RCON;
use GameQ\Server as GameQServer;
use HiHaHo\EncryptableTrait\Encryptable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use SquadMS\Servers\Data\ServerQueryResult;
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

        'start_seeding',
        'stop_seeding',

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

    protected ?ServerQueryResult $lastQueryResult = null;

    public function getConnectUrlAttribute(): string
    {
        return 'steam://connect/'.$this->host.':'.$this->game_port.'/';
    }

    public function getHasRconDataAttribute(): bool
    {
        return ! is_null($this->rcon_port) && ! is_null($this->rcon_password);
    }

    public function getLastQueryResultAttribute(): ServerQueryResult
    {
        if (is_null($this->lastQueryResult)) {
            $this->lastQueryResult = ServerQueryResult::load($this);
        }

        return $this->lastQueryResult;
    }

    public function getOnlineAttribute(): bool
    {
        /** @var ServerQueryResult */
        $result = $this->last_query_result;

        return $result->online();
    }

    /**
     * Scope a query to only include servers that have RCON connection information available.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasRconData($query)
    {
        return $query->whereNotNull('rcon_port')->whereNotNull('rcon_password');
    }

    /**
     * Get the messages that were sent on this server.
     */
    public function serverChatMessages(): HasMany
    {
        return $this->hasMany(ServerChatMessage::class);
    }

    /**
     * Get the Ban-Lists that do affect this server.
     */
    public function banLists(): HasManyThrough
    {
        return $this->hasManyThrough(BanList::class, ServerBanList::class);
    }

    /**
     * Get the GameQ server connection data well formed.
     *
     * @return array
     */
    public function getGameQData(): array
    {
        return [
            GameQServer::SERVER_TYPE    => 'squad',
            GameQServer::SERVER_HOST    => $this->host.':'.$this->game_port,
            GameQServer::SERVER_OPTIONS => [
                GameQServer::SERVER_OPTIONS_QUERY_PORT => $this->query_port,
            ],
        ];
    }

    /**
     * Initializes and returns a new RCON connection.
     *
     * @throws \Throwable
     */
    public function getRconConnection(): ?RCON
    {
        if ($this->has_rcon_data) {
            return new RCON(new ServerConnectionInfo($this->host, $this->rcon_port, $this->rcon_password), new RCONWorkerCommandRunner($this->id));
        } else {
            return null;
        }
    }

    public function clearLastQueryResultCache(): void
    {
        $this->lastQueryResult = null;
    }
}
