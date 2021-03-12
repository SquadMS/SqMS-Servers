<?php

namespace SquadMS\Servers\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DSG\SquadRCON\SquadServer;

class ServerCommand extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'parameters' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'command',
        'parameters',
        'run_after',
        'ran_at',
    ];

    /**
     * Get the related Server.
     * 
     * @return BelongsTo
     */
    public function server() : BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    public function getParametersAttribute()
    {
        return json_decode($this->getRawOriginal('parameters'));
        
    }

    public function setParametersAttribute($value)
    {
        $this->attributes['parameters'] = json_encode($value);
    }

    static function queue(Server $server, string $command, array $parameters = [], ?Carbon $runAfter = null) : ServerCommand
    {
        if (is_null($runAfter)) {
            $runAfter = Carbon::now();
        }

        return $server->serverCommands()->create([
            'command'    => $command,
            'parameters' => $parameters,
            'run_after'  => $runAfter,
        ]);
    }

    public function execute(SquadServer $connection) : bool
    {
        /* initialize the status as false */
        $status = false;

        /* Get the executor / method name to run */
        $executor = self::getExecutor($this->command);

        try {
            /* Get the Executor */
            $executor = self::getExecutor($this->command);

            /* Execute the command */
            $status = !!call_user_func_array([$connection, $executor], $this->parameters ?? []);
        } catch(\Throwable $e) {
            Log::warning('The RCON Command "' . $this->command . '" could not be run. Error: ' . $e->getMessage());
        }

        /* Set the ran_at attribute to prevent it from running again */
        $status &= $this->update([
            'ran_at' => Carbon::now(),
        ]);

        return $status;
    }

    static function getExecutor(string $command) : string
    {
        switch($command) {
            case 'ListPlayers':
                return 'listPlayers';
            case 'ListSquads':
                return 'listSquads';
            case 'AdminListDisconnectedPlayers':
                return 'listDisconnectedPlayers';
            case 'ShowNextMap':
                return 'nextMap';
            case 'AdminKick':
                return 'adminKick';
            case 'AdminKickById':
                return 'adminKickById';
            case 'AdminBan':
                return 'adminBan';
            case 'AdminBanById':
                return 'adminBanById';
            case 'AdminBroadcast':
                return 'adminBroadcast';
            case 'AdminRestartMatch':
                return 'adminRestartMatch';
            case 'AdminEndMatch':
                return 'adminEndMatch';
            case 'AdminChangeMap':
                return 'adminChangeMap';
            case 'AdminSetNextMap':
                return 'adminSetNextMap';
            case 'AdminSetMaxNumPlayers':
                return 'adminSetMaxNumPlayers';
            case 'AdminSetServerPassword':
                return 'adminSetServerPassword';
            case 'AdminSlomo':
                return 'adminSlomo';
            case 'AdminForceTeamChange':
                return 'adminForceTeamChange';
            case 'AdminForceTeamChangeById':
                return 'adminForceTeamChangeById';
            case 'AdminDemoteCommander':
                throw new \InvalidArgumentException('The given command is not yet supported.');
            case 'AdminDemoteCommanderById':
                throw new \InvalidArgumentException('The given command is not yet supported.');
            case 'AdminDisbandSquad':
                return 'adminDisbandSquad';
            case 'AdminRemovePlayerFromSquad':
                return 'adminRemovePlayerFromSquad';
            case 'AdminRemovePlayerFromSquadById':
                return 'adminRemovePlayerFromSquadById';
            case 'AdminWarn':
                return 'adminWarn';
            case 'AdminWarnById':
                return 'adminWarnById';
            default:
                throw new \InvalidArgumentException('The given command does not exist or is not supported.');
        }
    }
}
