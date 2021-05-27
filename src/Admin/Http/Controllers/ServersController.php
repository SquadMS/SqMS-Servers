<?php

namespace SquadMS\Servers\Admin\Http\Controllers;

use Illuminate\Routing\Controller;

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
        return view('sqms-servers::admin.pages.servers');
    }
}