<?php

namespace SquadMS\Servers\Http\Livewire;

use Livewire\Component;
use SquadMS\Servers\Models\Server;

class ServerEntry extends Component
{
    public Server $server;

    public ?string $bgClass = null;

    public function mount()
    {
        if ($this->server->last_query_result->online() && $this->server->last_query_result->level()) {
            $this->bgClass = 'bg-map-'.\SquadMS\Foundation\Helpers\LevelHelper::levelToClass($this->server->last_query_result->level());
        }
    }

    public function render()
    {
        return view('sqms-servers::livewire.server-entry');
    }
}
