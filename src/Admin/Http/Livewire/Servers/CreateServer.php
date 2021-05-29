<?php

namespace SquadMS\Foundation\Admin\Http\Livewire\RBAC;

use Illuminate\Support\Facades\Config;
use SquadMS\Foundation\Admin\Http\Livewire\Contracts\AbstractModalComponent;
use SquadMS\Servers\Repositories\ServerRepositoriy;

class CreateServer extends AbstractModalComponent
{
    public string $input = '';

    protected $rules = [
        'input' => 'required|string|unique:' . Config::get('sqms-servers.models.server') . ',name',
    ];

    public function createServer() {
        /* Validate the data first */
        $this->validate();

        /* Create the Server */
        ServerRepositoriy::getServerModelQuery()->create([
            'name' => $this->input,
        ]);

        /* Emit event */
        $this->emit('server:created');

        /* Reset state */
        $this->reset();
    }
    
    public function render()
    {
        return view('sqms-servers::admin.livewire.servers.create-server');
    }
}