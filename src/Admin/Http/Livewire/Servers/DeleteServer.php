<?php

namespace SquadMS\Servers\Admin\Http\Livewire\Servers;

use SquadMS\Foundation\Admin\Http\Livewire\Contracts\AbstractModalComponent;
use SquadMS\Servers\Models\Server;

class DeleteServer extends AbstractModalComponent
{
    public Server $server;

    public function deleteServer() {
        /* Delete the Server */
        $this->server->delete();    
        
        /* Hide the modal (backdrop) */
        $this->hideModal();

        /* Emit event */
        $this->emit('server:deleted');
    }
    
    public function render()
    {
        return view('sqms-servers::admin.livewire.servers.delete-server');
    }
}