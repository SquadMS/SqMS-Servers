<?php

namespace SquadMS\Servers\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class ServersController extends Controller
{
    /**
     * Shows the profile page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* Show home page */
        return View::make('sqms-servers::admin.pages.servers');
    }
}