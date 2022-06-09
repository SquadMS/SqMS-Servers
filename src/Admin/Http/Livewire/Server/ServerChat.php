<?php

namespace SquadMS\Servers\Admin\Http\Livewire\Server;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Livewire\Component;
use SquadMS\Foundation\SquadMS\Foundation\Models\SquadMSUser;
use SquadMS\Servers\Jobs\RCONAdminBroadcast;
use SquadMS\Servers\Models\Server;

class ServerChat extends Component
{
    use AuthorizesRequests;

    protected $listeners = [
        'refreshComponent'                                                             => '$refresh',
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

        /* Count remaining old messages before limiting */
        $count = (clone $query)->count();

        /* Query old messages */
        $newMessages = $query->limit(self::PAGE_SIZE)->get()->reverse();

        /* Determine if there are any old messages left */
        $this->hasOld = $count - $newMessages->count() > 0;

        /* Prepend old messages to the message list */
        $this->performanceAddMessages($newMessages);

        /* Magic event to refresh the component */
        $this->emitSelf('refreshComponent');
    }

    public function loadNew()
    {
        /** @var \Illuminate\Database\Query\Builder Get the query builder for the messages and order to oldest */
        $query = $this->getServerChatMessagesQuery()->oldest('time');

        /* Only select messages where id is bigger than oldest message */
        if (($newestMessage = $this->messages->last())) {
            $query->where('id', '>', $newestMessage->id);
        }

        /* Count remaining new messages */
        $count = (clone $query)->count();

        /* Initialize an empty collection to keep type safety */
        $newMessages = new Collection();

        /* Scroll locked should always load the newest messages */
        if ($this->scrollLock) {
            /* Get a full page of newest messages in order to overwrite */
            $newMessages = $query->latest('time')->limit(self::MAX_MESSAGES)->get()->reverse();

            /* Locked should not have any messages left since it is live */
            $this->hasNew = false;
        } else {
            /* Get a Page of new messages and reverse since we ordered by oldest */
            $newMessages = $query->limit(self::PAGE_SIZE)->get()->reverse();

            /* Determine if there are messages left that do not fit in */
            $this->hasNew = $count - $newMessages->count() > 0;
        }

        $this->performanceAddMessages($newMessages, false);

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

    private function performanceAddMessages(Collection &$newMessages, bool $prepend = true)
    {
        /* Determine if there will be old / new messages after this operation */
        if ($this->messages->count() + $newMessages->count() >= self::MAX_MESSAGES) {
            if ($prepend) {
                $this->hasNew = true;
            } else {
                $this->hasOld = true;
            }
        }

        /* Use the best strategy to update the messages */
        if ($newMessages->count() >= self::MAX_MESSAGES) {
            $this->messages = $newMessages;
        } else {
            /* Determine which collection is bigger / the base */
            $direction = $this->messages->count() > $newMessages->count();

            /* Flip prepend/append based on which collection is bigger */
            $prepend = $direction ? $prepend : !$prepend;

            /* Determine Iterator and Basis */
            $iterator = $direction ? $newMessages : $this->messages;
            $basis = $direction ? $this->messages : $newMessages;

            /* Push all new messages on the message list */
            foreach ($iterator  as $message) {
                /* Remove from start if there are too many messages */
                if ($basis->count() >= self::MAX_MESSAGES) {
                    if ($prepend) {
                        $basis->pop();
                    } else {
                        $basis->shift();
                    }
                }

                /* Add the message to the end */
                if ($prepend) {
                    $basis->prepend($message);
                } else {
                    $basis->push($message);
                }
            }

            $this->messages = $basis;
        }
    }

    private function getServerChatMessagesQuery(): HasMany
    {
        return $this->server->serverChatMessages()->latest('time');
    }
}
