<?php

namespace SquadMS\Servers\Admin\Http\Livewire\Servers;

use Illuminate\Support\Facades\View;
use Livewire\Component;
use SquadMS\Servers\Models\BanList;

class ServerEntry extends Component
{
    public BanList $banlist;

    protected $listeners = [
        'banlist:updated' => '$refresh',
    ];

    public function render()
    {
        return View::make('sqms-servers::admin.livewire.banlists.banlist-entry');
    }
}
