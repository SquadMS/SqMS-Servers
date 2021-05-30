<?php

namespace SquadMS\Servers\Admin\Http\Livewire\Servers;

use Livewire\Component;
use SquadMS\Servers\Models\Server;

class ServerEntry extends Component
{
    public Server $server;

    protected $listeners = [
        'server:updated' => '$refresh',
    ];
    
    public function render()
    {
        return view('sqms-servers::admin.livewire.servers.server-entry');
    }
}