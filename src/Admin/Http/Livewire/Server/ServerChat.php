<?php

namespace SquadMS\Servers\Admin\Http\Livewire\Server;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Livewire\Component;
use SquadMS\Servers\Jobs\RCONAdminBroadcast;
use SquadMS\Servers\Models\Server;

class ServerChat extends Component
{
    use AuthorizesRequests;

    protected $listeners = [
        'echo:ServerChatMessageCreated' => '$refresh',
    ];

    public Server $server;

    public ?Collection $messages = null;

    public string $message = '';

    public function mount()
    {
        $this->messages = $this->getServerChatMessagesQuery()->limit(50);
    }

    public function sendMessage(): void
    {
        $this->authorize('admin servers moderation broadcast');

        if (Config::het('sqms-servers.worker.enabled')) {
            RCONAdminBroadcast::dispatch($this->message);
        } else {
            // TODO, add server query based command queue.
        }
        
        $this->server->getRconConnection()->adminBroadcast($this->message);

        $this->message = '';
    }

    public function loadOld(): void
    {
        $query = $this->getServerChatMessagesQuery();

        if (($oldestMessage = $this->messages->first())) {
            $query->where('id', '<', $oldestMessage->id);
        }

        $this->messages->merge($query->limit(50)->get());
        
    }

    public function loadNew(): void
    {
        $query = $this->getServerChatMessagesQuery();

        if (($newestMessage = $this->messages->last())) {
            $query->where('id', '>', $newestMessage->id);
        }

        $this->messages->concat($query->limit(50)->get());
        
    }

    public function render()
    {
        return View::make('sqms-servers::admin.livewire.server.chat', [
            'messages' => $this->server->serverChatMessages()->latest('time')->cursorPaginate(25),
        ]);
    }

    private function getServerChatMessagesQuery(): HasMany
    {
        return $this->server->serverChatMessages()->latest();
    }
}