<?php

namespace SquadMS\Servers\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use SquadMS\Servers\Http\Requests\Server\UpdateServer;
use SquadMS\Servers\Http\Requests\Server\CreateServer;
use SquadMS\Servers\Http\Requests\Server\DeleteServer;
use SquadMS\Servers\Http\Requests\Server\EditServer;
use SquadMS\Servers\Http\Requests\Server\StoreServer;
use SquadMS\Servers\Models\Server;

class ServerController extends Controller
{
    /**
     * Display a listing of the Servers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.servers.index', [
            'servers' => Server::all()
        ]);
    }

    /**
     * Show the form for creating a new Server.
     *
     * @param  \SquadMS\Servers\Http\Requests\Server\CreateServer  $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateServer $request)
    {
        return view('admin.servers.create');
    }

    /**
     * Store a newly created Server in storage.
     *
     * @param  \SquadMS\Servers\Http\Requests\Server\StoreServer  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreServer $request)
    {
        Server::create($request->validated());

        return redirect()->route('admin.servers.index')->withSuccess('Server erfolgreich erstellt.');
    }

    /**
     * Display the specified Server.
     *
     * @param  \SquadMS\Servers\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function show(Server $server)
    {
        return redirect()->route('admin.servers.index');
    }

    /**
     * Show the form for editing the specified Server.
     *
     * @param  \SquadMS\Servers\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function edit(EditServer $request, Server $server)
    {
        return view('admin.servers.edit', [
            'server' => $server,
        ]);
    }

    /**
     * Update the specified Server in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \SquadMS\Servers\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateServer $request, Server $server)
    {
        /* Update the Server */
        $server->update($request->validated());

        /* Redirect back to Server list */
        return redirect()->route('admin.servers.index')->withSuccess('Server erfolgreich bearbeitet.');
    }

    /**
     * Remove the specified Server from storage.
     *
     * @param  \SquadMS\Servers\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteServer $request, Server $server)
    {
        $server->delete();

        return redirect()->route('admin.servers.index')->withSuccess('Server erfolgreich gelöscht.');
    }
}