<?php

namespace SquadMS\DefaultTheme\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use SquadMS\Servers\Models\Server;

class ServerController extends Controller
{
    /**
     * Shows the servers population
     *
     * @return \Illuminate\Http\Response
     */
    public function population(Server $server)
    {
        /* Show population view as text */
        return Response::make(View::make('sqms-servers::components.player-list.population', [
            'server' => $server,
        ])->render(), 200, [
            'Content-Type' => 'text/plain'
        ]);
    }
}
