<?php

namespace SquadMS\Servers\Http\Livewire;

use Livewire\Component;
use SquadMS\Servers\Models\Server;

class ServerEntry extends Component
{
    public Server $server;

    public function render()
    {
        return view('sqms-servers::livewire.server-entry');
    }
}
