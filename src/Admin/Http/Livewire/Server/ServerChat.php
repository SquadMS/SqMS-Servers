<?php

namespace SquadMS\Servers\Admin\Http\Livewire\Server;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Livewire\Component;
use SquadMS\Foundation\Contracts\SquadMSUser;
use SquadMS\Servers\Jobs\RCONAdminBroadcast;
use SquadMS\Servers\Models\Server;

class ServerChat extends Component
{
    use AuthorizesRequests;

    protected $listeners = [
        'refreshComponent'                                                            => '$refresh',
        'echo-private:server-chat,.SquadMS\\Servers\\Events\\ServerChatMessageCreated' => 'loadNew',
    ];

    const MAX_MESSAGES = 100;
    const PAGE_SIZE = 25;

    public Server $server;

    public ?Collection $messages = null;

    public bool $scrollLock = true;

    public bool $hasOld = true;
    public bool $hasNew = false;

    public string $message = '';

    public function mount()
    {
        $this->messages = $this->getServerChatMessagesQuery()->limit(self::PAGE_SIZE)->get()->reverse();
        $this->hasOld = $this->messages->count() === self::PAGE_SIZE;
    }

    public function sendMessage()
    {
        $this->authorize('admin servers moderation broadcast');

        if (Config::get('sqms-servers.worker.enabled')) {
            RCONAdminBroadcast::dispatch($this->server, SquadMSUser::current(), $this->message);
        }

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

        /* Query old messages */
        $newMessages = $query->limit(self::PAGE_SIZE)->get();

        /* Determine if there are any old messages left */
        $this->hasOld = $newMessages->count() === self::PAGE_SIZE;

        /* Prepend old messages to the message list */
        $this->prependMessages($newMessages);

        /* Magic event to refresh the component */
        $this->emitSelf('refreshComponent');
    }

    public function loadNew()
    {
        /** @var \Illuminate\Database\Query\Builder Get the query builder for the messages and order to oldest */
        $query = $this->getServerChatMessagesQuery();

        /* Only select messages where id is bigger than oldest message */
        if (($newestMessage = $this->messages->last())) {
            $query->where('id', '>', $newestMessage->id);
        }  

        /* Initialize an empty collection to keep type safety */
        $newMessages = new Collection();

        /* Scroll locked should always load the newest messages */
        if ($this->scrollLock) {
            $newMessages = $query->oldest('time')->limit(self::MAX_MESSAGES)->get();

            /* Locked should not have any messages left since it is live */
            $this->hasNew = false;
        } else if ($this->messages->count() < self::MAX_MESSAGES) {
            $newMessages = $query->limit(self::MAX_MESSAGES - $this->messages->count())->get()->reverse();

            /* Determine if there are messages left that do not fit in */
            $this->hasNew = (clone $query)->count() - $newMessages->count() > self::MAX_MESSAGES;
        }

        $this->appendMessages($newMessages);

        /* Magic event to refresh the component */
        $this->emitSelf('refreshComponent');

        /* Emit loaded event */
        $this->emitSelf('loaded-new');
    }

    public function lockScroll()
    {
        $this->scrollLock = true;

        $this->loadNew();
    }

    public function render()
    {
        return View::make('sqms-servers::admin.livewire.server.chat');
    }

    private function prependMessages(Collection $messages): void
    {
        /* Prepend old messages to the message list */
        foreach ($messages as $message) {
            /* Remove from end if there are too many messages */
            if ($this->messages->count() >= self::MAX_MESSAGES) {
                $this->messages->pop();
            }

            /* Add the message */
            $this->messages->prepend($message);
        }
    }

    private function appendMessages(Collection $messages): void
    {
        /* Determine the best strategy based on the amount of messages */
        if ($messages->count() === self::MAX_MESSAGES) {
            /* Directly assign it as we do not need old messages */
            $this->messages = $messages;
        } else {
            /* Push all new messages on the message list */
            foreach ($messages as $message) {
                /* Remove from start if there are too many messages */
                if ($this->messages->count() >= self::MAX_MESSAGES) {
                    $this->messages->shift();
                }

                /* Add the message to the end */
                $this->messages->push($message);
            }
        }
    }

    private function getServerChatMessagesQuery(): HasMany
    {
        return $this->server->serverChatMessages()->latest('time');
    }
}
