<?php

namespace SquadMS\Servers\Admin\Http\Livewire\Servers;

use Illuminate\Support\Facades\View;
use Livewire\Component;
use Livewire\WithPagination;
use SquadMS\Servers\Repositories\ServerRepositoriy;

class ServerList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'server:created' => '$refresh',
        'server:deleted' => '$refresh',
    ];

    public function render()
    {
        return View::make('sqms-servers::admin.livewire.servers.server-list', [
            'servers' => ServerRepositoriy::getModelQuery()->paginate(10),
        ]);
    }
}
