<?php

namespace SquadMS\Servers\Admin\Http\Livewire\Servers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\View;
use SquadMS\Foundation\Admin\Http\Livewire\Contracts\AbstractModalComponent;
use SquadMS\Servers\Models\Server;

class DeleteServer extends AbstractModalComponent
{
    use AuthorizesRequests;
    
    public Server $server;

    public function deleteServer()
    {
        /* Authorize the action */
        $this->authorize('delete', $this->server);

        /* Delete the Server */
        $this->server->delete();    
        
        /* Hide the modal (backdrop) */
        $this->hideModal();

        /* Emit event */
        $this->emit('server:deleted');
    }
    
    public function render()
    {
        return View::make('sqms-servers::admin.livewire.servers.delete-server');
    }
}