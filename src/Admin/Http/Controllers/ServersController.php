<?php

namespace SquadMS\Servers\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ServersController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Shows the profile page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* Authorize the action */
        $this->authorize(Config::get('sqms-servers.permissions.module') . ' admin servers');

        /* Show home page */
        return View::make('sqms-servers::admin.pages.servers');
    }
}