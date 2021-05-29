<?php

namespace SquadMS\Servers\Admin\Http\Livewire\Servers;

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
        return view('sqms-servers::admin.livewire.servers.server-list', [
            'servers' => ServerRepositoriy::getServerModelQuery()->paginate(10),
        ]);
    }
}