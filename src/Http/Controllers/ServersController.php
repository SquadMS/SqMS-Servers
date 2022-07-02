<?php

namespace SquadMS\Servers\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use SquadMS\Servers\Models\Server;

class ServersController extends Controller
{
    /**
     * Shows the servers page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* Show home page */
        return View::make('sqms-servers::servers', [
            'servers' => Server::all(),
        ]);
    }

    /**
     * Shows the server page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Server $server)
    {
        /* Show home page */
        return View::make('sqms-servers::server', [
            'server' => $server,
        ]);
    }
}
