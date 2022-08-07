<?php

namespace SquadMS\Servers\Http\Livewire;

use Livewire\Component;
use SquadMS\Foundation\View\Components\Layouts\App;
use SquadMS\Servers\Models\Server as ServerModel;

class Server extends Component
{
    public ServerModel $server;

    public function render()
    {
        return view('sqms-servers::livewire.server')
            ->layout(App::class);
    }
}
