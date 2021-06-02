<?php

namespace SquadMS\Servers\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use SquadMS\Servers\Models\Server;

class APIServersController extends Controller
{
    /**
     * Returns all servers with their rcon information included.
     *
     * @return \Illuminate\Http\Response
     */
    public function servers()
    {
        return Response::json(Server::hasRconInfo()->get());
    }
}