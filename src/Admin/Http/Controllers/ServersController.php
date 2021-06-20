<?php

namespace SquadMS\Servers\Admin\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use SquadMS\Servers\Models\Server;

class ServersController extends Controller
{
    use AuthorizesRequests;

    /**
     * Shows the Servers overview page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* Authorize the action */
        $this->authorize(Config::get('sqms-servers.permissions.module').' admin servers');

        /* Show home page */
        return View::make('sqms-servers::admin.pages.servers');
    }

    /**
     * Shows the Server detail page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Server $server)
    {
        /* Authorize the action */
        $this->authorize(Config::get('sqms-servers.permissions.module').' admin servers moderation');

        /* Show home page */
        return View::make('sqms-servers::admin.pages.server', [
            'server' => $server,
        ]);
    }
}
