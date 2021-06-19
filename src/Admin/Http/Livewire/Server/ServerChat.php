<?php

namespace SquadMS\Servers\Admin\Http\Livewire\Server;

use Illuminate\Support\Facades\View;
use SquadMS\Foundation\Admin\Http\Livewire\Contracts\AbstractModalComponent;
use SquadMS\Servers\Models\Server;

class ServerChat extends AbstractModalComponent
{
    protected $listeners = [
        'echo:ServerChatMessageCreated' => '$refresh',
    ];

    public Server $server;

    public function render()
    {
        return View::make('sqms-servers::admin.livewire.server.chat', [
            'messages' => $this->server->serverChatMessages()->paginate(25),
        ]);
    }
}
