<?php

namespace SquadMS\Servers\Jobs;

use GameQ\GameQ;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use SquadMS\Foundation\Facades\SDKDataReader;
use SquadMS\Servers\Data\ServerQueryResult;
use SquadMS\Servers\Events\ServerQueried;
use SquadMS\Servers\Models\Server;
use SquadMS\Servers\Services\ServerQueryService;

class QueryServer implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

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
        /* Query the Server and dispatch event with the result */
        ServerQueried::dispatch($this->queryServer());
    }

    /**
     * Queries the Server and retrieves  information. Instead of
     * throwing an exception it will return its success status.
     *
     * @return bool
     */
    private function queryServer(): ServerQueryResult
    {
        try {
            /* Steam Query / A2S */
            $gameq = new GameQ();
            $gameq->addServer($this->server->getGameQData());
            $result = $gameq->process();
            $status = array_values($result)[0];

            /* Build a new QueryResult from the response */
            $serverQueryResult = new ServerQueryResult(
                server: $this->server,
                name: Arr::get($status, 'gq_hostname') ?? $this->server->name,
                online: Arr::get($status, 'gq_online', false),
                slots: intval(Arr::get($status, 'NUMPUBCONN', 0)),
                reserved: intval(Arr::get($status, 'NUMPRIVCONN', 0)),
                count: intval(Arr::get($status, 'PlayerCount_i', 0)),
                publicQueue: intval(Arr::get($status, 'PublicQueue_i', 0)),
                reservedQueue: intval(Arr::get($status, 'ReservedQueue_i', 0)),
                layer: Arr::get($status, 'gq_mapname'),
                level: SDKDataReader::layerToLevel($this->layer)
            );

            /* Check if the server is online and has RCON information configured */
            if ($serverQueryResult->online && $this->server->has_rcon_data) {
                /* Initialize a new RCON connection to the server */
                $rcon = $this->server->getRconConnection();

                /* Run RCON commands and add information to the ServerQueryResult */
                $nextMapInfo = $rcon->showNextMap();
                if (Arr::get($nextMapInfo, 'nextLayer')) {
                    /* Only set if it is not Jensens Range, use current or default in that case */
                    if (Arr::get($nextMapInfo, 'layer') !== 'Jensen\'s Training Range') {
                        $serverQueryResult->nextLayer = SDKDataReader::getLayer(Arr::get($nextMapInfo, 'layer'));
                    } else {
                        $serverQueryResult->nextLayer = $serverQueryResult->nextLayer ?? 'JensensRange_GB-MIL';
                    }
                }
                $serverQueryResult->nextLevel = Arr::get($nextMapInfo, 'nextLevel');
                $serverQueryResult->population = $rcon->serverPopulation();
            }

            return $serverQueryResult;
        } catch (\Throwable $e) {
            Log::debug('[QueryServer] Error during query: '.$e->getMessage().PHP_EOL.$e->getTraceAsString());

            return new ServerQueryResult(
                server: $this->server,
                name: $this->server->name,
            );
        } finally {
            /* Close connections to be sure */
            if ($rcon) {
                $rcon->disconnect();
                unset($rcon);
            }

            if ($gameq) {
                unset($gameq);
            }
        }
    }
}
