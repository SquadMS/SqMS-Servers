<?php

namespace SquadMS\Servers\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use SquadMS\Servers\Data\QueryResult;
use SquadMS\Servers\Models\Server;
use SquadMS\Servers\Services\ServerQueryService;
use Carbon\Carbon;
use DSG\SquadRCON\SquadServer;
use GameQ\GameQ;

class QueryServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Server */
    protected Server $server;

    /** @var QueryResult */
    protected QueryResult $queryResult;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Server $server)
    {
        $this->server = $server;

        $this->queryResult = new QueryResult($this->server, false);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /* Query the Server */
        $result = $this->queryServer();

        /* Process the results */
        ServerQueryService::processResult($result);
    }

    /**
     * Queries the Server and retrieves  information. Instead of 
     * throwing an exception it will return its success status.
     *
     * @return boolean
     */
    private function queryServer() : QueryResult
    {
        /** @var \DSG\SquadRCON\SquadServer|null */
        $rcon = null;
        
        /** @var GameQ|null */
        $gameq = null;

        try {
            /* Steam Query */
            $gameq = new GameQ();
            $gameq->addServer($this->server->getGameQData());
            $result = $gameq->process();
            $status = array_values($result)[0];

            /* Initialize RCON */
            $rcon = $this->server->getRconConnection();

            $this->queryResult = new QueryResult($this->server, true, $status['gq_hostname'], intval($status['NUMPUBCONN']), intval($status['NUMPRIVCONN']), (isset($status['PublicQueue_i']) ? intval($status['PublicQueue_i']) : 0) + (isset($status['ReservedQueue_i']) ? intval($status['ReservedQueue_i']) : 0), $rcon->currentMap(), $rcon->nextMap(), $rcon->serverPopulation(), $status['gq_joinlink']);

            /* Run commands */
            $this->runCommands($rcon);
        } catch (\Throwable $e) {
            Log::error('[QueryServer] Error during query: ' . $e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }

        /* Close connections to be sure */
        if ($rcon) {
            $rcon->disconnect();
            unset($rcon);
        }

        if ($gameq) {
            unset($gameq);
        }

        return $this->queryResult;
    }

    /**
     * Runs queued commands on the server.
     *
     * @param SquadServer $connection
     * @return void
     */
    private function runCommands(SquadServer $connection) : void
    {
        $commands = $this->server->serverCommands()
                    ->whereNull('ran_at')
                    ->where('run_after', '<', Carbon::now())
                    ->get();
        
        foreach ($commands as $command) {
            $command->execute($connection);
        }
    }
}