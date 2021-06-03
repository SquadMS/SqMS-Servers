<?php

namespace SquadMS\Servers\RCONCommandRunners;

use DSG\SquadRCON\Contracts\ServerCommandRunner;
use DSG\SquadRCON\Exceptions\RConException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RCONWorkerCommandRunner implements ServerCommandRunner
{
    private int $serverId;

    function __construct(int $serverId)
    {
        $this->serverId = $serverId;
    }

    /**
     * ListSquads command. Returns an array
     * of Teams containing Squads. The output
     * can be given to the listPlayers method
     * to add and reference the Player instances.
     *
     * @return Team[]
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function listSquads() : string
    {
        return $this->rcon('ListSquads');
    }

    /**
     * ListPlayers command, returns an array
     * of Player instances. The output of
     * ListSquads can be piped into it to
     * assign the Players to their Team/Squad.
     *
     * @return string
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function listPlayers() : string
    {
        /* Execute the ListPlayers command and get the response */
        return $this->rcon('ListPlayers');
    }

    /**
     * ListDisconnectedPlayers command, returns an array
     * of disconnected Player instances.
     *
     * @return string
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function listDisconnectedPlayers() : string
    {
        return $this->rcon('AdminListDisconnectedPlayers');
    }

    /**
     * AdmiNkick command.
     * Kick a Player by Name or Steam64ID
     * 
     * @param string $nameOrSteamId
     * @param string $reason
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminKick(string $nameOrSteamId, string $reason = '') : bool
    {
        return $this->_consoleCommand('AdminKick', $nameOrSteamId . ' ' . $reason, 'Kicked player ');
    }

    /**
     * AdminKickById command.
     * Broadcasts the given message on the server.
     * 
     * @param int $id
     * @param string $reason
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminKickById(int $id, string $reason = '') : bool
    {
        return $this->_consoleCommand('AdminKickById', $id . ' ' . $reason, 'Kicked player ');
    }

    /**
     * AdminBan command.
     * Bans the given Player from the Server.
     * 
     * @param string $msg
     * @param string $reason
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminBan(string $nameOrSteamId, string $duration = '1d', string $reason = '') : bool
    {
        return $this->_consoleCommand('AdminBan', $nameOrSteamId . ' ' . $duration . ' ' . $reason, 'Banned player ');
    }

    /**
     * AdminBanById command.
     * Bans the given Player from the Server.
     * 
     * @param int $id
     * @param string $reason
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminBanById(int $id, string $duration = '1d', string $reason = '') : bool
    {
        return $this->_consoleCommand('AdminBanById', $id . ' ' . $duration . ' ' . $reason, 'Banned player ');
    }

    /**
     * ShowCurrentMap command.
     * Gets the current level and layer.
     * 
     * @return array
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function showCurrentMap() : string
    {
        return $this->rcon('ShowCurrentMap');
    }

    /**
     * ShowNextMap command.
     * Gets the current and next map.
     * 
     * @return array
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function showNextMap() : string
    {
        return $this->rcon('ShowNextMap');
    }

    /**
     * AdminBroadcast command.
     * Broadcasts the given message on the server.
     * 
     * @param string $msg
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminBroadcast(string $msg) : bool
    {
        return $this->_consoleCommand('AdminBroadcast', $msg, 'Message broadcasted');
    }

    /**
     * AdminRestartMatch command.
     * Broadcasts the given message on the server.
     *
     * @return boolean
     */
    public function adminRestartMatch() : bool
    {
        return $this->_consoleCommand('AdminRestartMatch', '', 'Game restarted');
    }

    /**
     * AdminRestartMatch command.
     * Broadcasts the given message on the server.
     *
     * @return boolean
     */
    public function adminEndMatch() : bool
    {
        return $this->_consoleCommand('AdminEndMatch', '', 'Match ended');
    }

    /**
     * AdminSetMaxNumPlayers command.
     * Sets the max amount of players (public).
     *
     * @param int $slots How many public slots ther should be.
     * @return boolean
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminSetMaxNumPlayers(int $slots) : bool
    {
        return $this->_consoleCommand('AdminSetMaxNumPlayers', $slots, 'Set MaxNumPlayers to ' . $slots);
    }

    /**
     * AdminSetServerPassword command.
     * Sets the password of the server.
     *
     * @param string $password
     * @return boolean
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminSetServerPassword(string $password) : bool
    {
        return $this->_consoleCommand('AdminSetServerPassword', $password, 'Set server password to ' . $password);
    }

    /**
     * AdminChangeMap command
     * Immediately changes the current map to the given map.
     * @param string $map
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminChangeMap(string $map) : bool
    {
        return $this->_consoleCommand('AdminChangeMap', $map, 'Changed map to');
    }

    /**
     * AdminSetNextMap command.
     * Temporarily overwrites the next map in the
     * MapRotations, effecively changing the next map.
     * 
     * @param string $map
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminSetNextMap(string $map) : bool
    {
        return $this->_consoleCommand('AdminSetNextMap', $map, 'Set next map to');
    }

    /**
     * AdminSlomo command.
     * Sets the game speed with the AdminSlomo
     * command. Providing no parameter will set
     * the speed to default.
     *
     * @param float $timeDilation
     * @return boolean
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    public function adminSlomo(float $timeDilation = 1.0) : bool
    {
        return $this->_consoleCommand('AdminSlomo', $timeDilation);
    }

    /**
     * AdminForceTeamChange command.
     * Forces a player to the opposite team
     * by providing the name or steamid.
     *
     * @param string $nameOrSteamId
     * @return boolean
     */
    public function adminForceTeamChange(string $nameOrSteamId) : bool
    {
        return $this->_consoleCommand('AdminForceTeamChange', $nameOrSteamId, 'Forced team change for player ');
    }

    /**
     * AdminForceTeamChangeById command.
     * Forces a player to the opposite team
     * by providing the ingame Player id.
     *
     * @param integer $playerId
     * @return boolean
     */
    public function adminForceTeamChangeById(int $playerId) : bool
    {
        return $this->_consoleCommand('AdminForceTeamChangeById', $playerId, 'Forced team change for player ');
    }

    /**
     * AdminDemoteCommander command.
     * Demotes a player from the commander slot
     * by providing the name or steamid.
     *
     * @param string $playerName
     * @return boolean
     */
    //public function adminDemoteCommander(string $nameOrSteamId) : bool
    //{
    //    return $this->_consoleCommand('AdminDemoteCommander', $nameOrSteamId, 'Forced team change for player ');
    //}

    /**
     * AdminDemoteCommanderById command.
     * Demotes a player from the commander slot
     * by providing the ingame Player id.
     *
     * @param integer $playerId
     * @return boolean
     */
    //public function adminDemoteCommanderById(int $playerId) : bool
    //{
    //    return $this->_consoleCommand('AdminDemoteCommanderById', $playerId, 'Forced team change for player ');
    //}

    /**
     * AdminDisbandSquad command.
     * Disbands a Squad by providing the Team id  / index & Squad id / index.
     *
     * @param integer $teamId
     * @param integer $squadId
     * @return boolean
     */
    public function adminDisbandSquad(int $teamId, int $squadId) : bool
    {
        return $this->_consoleCommand('AdminDisbandSquad', $teamId . ' ' . $squadId, 'Remote admin disbanded squad ' . $squadId . ' on team ' . $teamId . ', named "');
    }

    /**
     * AdminRemovePlayerFromSquad command.
     * Removes a Player from his Squad by providing
     * the Player name.
     *
     * @param string $playerName
     * @return boolean
     */
    public function adminRemovePlayerFromSquad(string $playerName) : bool
    {
        return $this->_consoleCommand('AdminRemovePlayerFromSquad', $playerName, 'Player ', ' was removed from squad');
    }

    /**
     * AdminRemovePlayerFromSquadById command.
     * Removes a player from his Squad by providing
     * the ingame Player id.
     *
     * @param integer $playerId
     * @return boolean
     */
    public function adminRemovePlayerFromSquadById(int $playerId) : bool
    {
        return $this->_consoleCommand('AdminRemovePlayerFromSquadById', $playerId, 'Player ', ' was removed from squad');
    }

    /**
     * AdminWarn command.
     * Warns a Player by providing his name / steamid
     * and a message.
     *
     * @param string $nameOrSteamId
     * @param string $warnReason
     * @return boolean
     */
    public function adminWarn(string $nameOrSteamId, string $warnReason) : bool
    {
        return $this->_consoleCommand('AdminWarn', $nameOrSteamId . ' ' . $warnReason, 'Remote admin has warned player ');
    }

    /**
     * AdminWarnById command.
     * Warns a Player by providing his ingame Player id
     * and a message.
     *
     * @param integer $playerId
     * @param string $warnReason
     * @return boolean
     */
    public function adminWarnById(int $playerId, string $warnReason) : bool
    {
        return $this->_consoleCommand('AdminWarnById', $playerId . ' ' . $warnReason, 'Remote admin has warned player ');
    }

    /**
     * Helper method to run Console commands with an expected response.
     * 
     * @param string $cmd
     * @param string $param
     * @param string $expected
     * @return bool
     * @throws \DSG\SquadRCON\Exceptions\RConException
     */
    private function _consoleCommand(string $cmd, string $param, ?string $expectedStart = null, ?string $expectedEnd = null) : bool
    {
        /* Execute the RCON command */
        $response = $this->rcon($cmd . ' ' . $param);

        if (is_null($response)) {
            return false;
        }

        /* Clean NULL characters from the response */
        $cleaned = str_replace("\0", '', $response);

        /* Validate */
        if (is_null($expectedStart) && is_null($expectedEnd)) {
            return !mb_strlen($cleaned);
        } else {
            $status = true;

            if (!is_null($expectedStart)) {
                $status &= substr($cleaned, 0, strlen($expectedStart)) == $expectedStart;
            }
            
            if (!is_null($expectedEnd)) {
                $status &= substr($cleaned, -strlen($expectedEnd)) == $expectedEnd;
            }

            return $status;
        }
    }

    private function rcon(string $command) : string
    {
        $response = Http::post(Config::get('sqms-servers.worker.host') . ':' . Config::get('sqms-servers.worker.port') . '/execute-command', [
            'id' => $this->serverId,
            'command' => $command,
        ]);

        if ($response->successful()) {
            $json = $response->json();
            if (!is_null($json)) {
                return $response->json();
            } else {
                $error = 'RCON Command Error: Empty response';
            }
        } else {
            $error = 'RCON Command Error: ' . $response->body();
        }

        /* Log the Error */
        Log::error($error);

        /* Throw exception */
        throw new RConException($error);
    }

    /**
     * Disconnects the runner from any squad server instance.
     *
     * @return void
     */
    public function disconnect() : void
    {
        return;
    }
}