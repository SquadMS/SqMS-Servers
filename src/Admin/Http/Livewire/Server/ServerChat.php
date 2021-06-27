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
        'refreshComponent' => '$refresh',
        'echo-private:server-chat,SquadMS\\Servers\\Events\\ServerChatMessageCreated' => 'loadNew',
    ];

    public Server $server;

    public ?Collection $messages = null;

    public bool $hasOld = true;

    public string $message = '';

    public function mount()
    {
        $this->messages = $this->getServerChatMessagesQuery()->limit(50)->get()->reverse();
        $this->hasOld = $this->messages->count() === 50;
    }

    public function sendMessage()
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

    public function loadOld()
    {
        /* Get the query builder for the messages */
        $query = $this->getServerChatMessagesQuery();

        /* Only select messages where id is smaller than oldest message */
        if (($oldestMessage = $this->messages->first())) {
            $query->where('id', '<', $oldestMessage->id);
        }

        /* Query old messages and reverse for correct order */
        $newMessages = $query->limit(50)->get()->reverse();

        /* Determine if there are any old messages left */
        $this->hasOld = $newMessages->count() === 50;

        /* Prepend old messages to the message list */
        foreach ($newMessages as $message) {
            $this->messages->prepend($message);
        }
        
        /* Magic event to refresh the component */
        $this->emitSelf('refreshComponent');
    }

    public function loadNew()
    {
        /* Get the query builder for the messages */
        $query = $this->getServerChatMessagesQuery();

        /* Only select messages where id is bigger than oldest message */
        if (($newestMessage = $this->messages->last())) {
            $query->where('id', '>', $newestMessage->id);
        }

        /* Push all new messages on the message list */
        $this->messages = $this->messages->concat($query->get()->reverse());
        
        /* Magic event to refresh the component */
        $this->emitSelf('refreshComponent');

        /* Emit loaded event */
        $this->dispatchBrowserEvent('loaded-new');
    }

    public function render()
    {
        return View::make('sqms-servers::admin.livewire.server.chat');
    }

    private function getServerChatMessagesQuery(): HasMany
    {
        return $this->server->serverChatMessages()->latest();
    }
}