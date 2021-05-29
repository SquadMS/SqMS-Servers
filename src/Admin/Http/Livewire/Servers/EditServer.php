<?php

namespace SquadMS\Servers\Admin\Http\Livewire\Servers;

use Illuminate\Support\Facades\Config;
use SquadMS\Foundation\Admin\Http\Livewire\Contracts\AbstractModalComponent;
use SquadMS\Servers\Models\Server;

class EditServer extends AbstractModalComponent
{
    public Server $server;

    protected $rules = [
        'server.name' => null // TODO: Remove this somehow...
    ];

    public function updateServer() {
        /* Validate the data first */
        $this->validate([
            'server.name' => 'required|string|unique:' . Config::get('sqms-servers.models.server') . ',name,' . $this->server->id,
        ]);
        
        /* Create the Server */
        $this->server->save();

        $this->hideModal();

        /* Emit event */
        $this->emitUp('server:updated');
    }
    
    public function render()
    {
        return view('sqms-servers::admin.livewire.servers.edit-server');
    }
}