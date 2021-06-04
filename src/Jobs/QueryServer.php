<?php

namespace SquadMS\Servers\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use GameQ\GameQ;
use SquadMS\Servers\Data\ServerQueryResult;
use SquadMS\Servers\Models\Server;
use SquadMS\Servers\Services\ServerQueryService;

class QueryServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Server $server;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
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
    private function queryServer() : ServerQueryResult
    {
        /** @var \DSG\SquadRCON\SquadServer|null */
        $rcon = null;
        
        /** @var GameQ|null */
        $gameq = null;

        $serverQueryResult = new ServerQueryResult($this->server);

        try {
            /* Steam Query / A2S */
            $gameq = new GameQ();
            $gameq->addServer($this->server->getGameQData());
            $result = $gameq->process();
            $status = array_values($result)[0];

            /* Build a new QueryResult from the response */
            $serverQueryResult->setQueryData(
                Arr::get($status, 'gq_online', false),
                Arr::get($status, 'gq_hostname') ?? 'Squad Dedicated Server',
                intval(Arr::get($status, 'NUMPUBCONN', 0)),
                intval(Arr::get($status, 'NUMPRIVCONN', 0)),
                intval(Arr::get($status, 'PlayerCount_i', 0)),
                intval(Arr::get($status, 'PublicQueue_i', 0)) + intval(Arr::get($status, 'ReservedQueue_i', 0)),
            );

            /* Check if the server is online and has RCON information configured */
            if ($serverQueryResult->online() && $this->server->has_rcon_data) {
                /* Initialize a new RCON connection to the server */
                $rcon = $this->server->getRconConnection();

                /* Run RCON commands and add information to the ServerQueryResult */
                $serverQueryResult->setRCONData($rcon->showCurrentMap(), $rcon->showNextMap(), $rcon->serverPopulation());
            }
        } catch (\Throwable $e) {
            Log::debug('[QueryServer] Error during query: ' . $e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }

        /* Close connections to be sure */
        if ($rcon) {
            $rcon->disconnect();
            unset($rcon);
        }

        if ($gameq) {
            unset($gameq);
        }

        return $serverQueryResult;
    }
}