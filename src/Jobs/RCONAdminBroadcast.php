<?php

namespace SquadMS\Servers\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use SquadMS\Servers\Models\Server;

class RCONAdminBroadcast implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected Server $server;

    protected string $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Server $server, string $message)
    {
        $this->server = $server;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            /** @var \DSG\SquadRCON\SquadServer|null */
            $rcon = null;

            /* Check if the server is online and has RCON information configured */
            if ($this->server->online && $this->server->has_rcon_data) {
                /* Initialize a new RCON connection to the server */
                $rcon = $this->server->getRconConnection();

                /* Run RCON commands and add information to the ServerQueryResult */
                $rcon->adminBroadcast($this->message);
            }
        } catch (\Throwable $e) {
            Log::debug('[QueryServer] Error during query: '.$e->getMessage().PHP_EOL.$e->getTraceAsString());
        } finally {
            /* Close connections to be sure */
            if ($rcon) {
                $rcon->disconnect();
                unset($rcon);
            }
        }
    }
}
