<?php

namespace SquadMS\Servers\Admin\Http\Livewire\Servers;

use Illuminate\Support\Facades\View;
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
        return View::make('sqms-servers::admin.livewire.servers.server-entry');
    }
}