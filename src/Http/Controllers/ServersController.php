<?php

namespace SquadMS\Servers\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use SquadMS\Servers\Models\Server;

class ServersController extends Controller
{
    /**
     * Shows the servers page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* Show home page */
        return view(Config::get('sqms.theme') . '::pages.servers', [
            'servers' => Server::all(),
        ]);
    }

    /**
     * Shows the server page
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Server $server)
    {
        /* Show home page */
        return view(Config::get('sqms.theme') . '::pages.server', [
            'server' => $server,
        ]);
    }
}