<?php

namespace SquadMS\Servers\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use SquadMS\Foundation\Models\SquadMSUser;
use SquadMS\Servers\Models\Server;
use SquadMS\Servers\Models\ServerChatMessage;

class RCONAdminBroadcast implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected Server $server;

    protected SquadMSUser $user;

    protected string $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Server $server, SquadMSUser $user, string $message)
    {
        $this->server = $server;
        $this->user = $user;
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
                $rcon->adminBroadcast('Admin: '.$this->message);

                /* Save Broadcast as ServerChatMessage */
                $this->server->serverChatMessages()->create([
                    'user_id' => $this->user->id,

                    'type' => 'Broadcast',

                    'name'    => 'Admin',
                    'content' => $this->message,

                    'time' => Carbon::now(),
                ]);
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
