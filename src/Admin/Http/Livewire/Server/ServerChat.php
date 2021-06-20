<?php

namespace SquadMS\Servers\Admin\Http\Livewire\Server;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\View;
use Livewire\WithPagination;
use Livewire\Component;
use SquadMS\Servers\Models\Server;

class ServerChat extends Component
{
    use WithPagination, AuthorizesRequests;

    protected $listeners = [
        'echo:ServerChatMessageCreated' => '$refresh',
    ];

    public Server $server;

    public string $message = '';

    public function sendMessage(): void
    {
        $this->authorize('admin servers moderation broadcast');

        // Send Broadcast

        $this->message = '';
    }

    public function render()
    {
        return View::make('sqms-servers::admin.livewire.server.chat', [
            'messages' => $this->server->serverChatMessages()->cursorPaginate(25),
        ]);
    }
}
