<?php

namespace SquadMS\Servers\Admin\Http\Livewire\Servers;

use Illuminate\Support\Facades\View;
use Livewire\Component;
use Livewire\WithPagination;
use SquadMS\Servers\Repositories\BanListRepository;

class BanListList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'banList:created' => '$refresh',
        'banList:deleted' => '$refresh',
    ];

    public function render()
    {
        return View::make('sqms-servers::admin.livewire.banlists.banlist-list', [
            'banlists' => BanListRepository::getModelQuery()->paginate(10),
        ]);
    }
}
