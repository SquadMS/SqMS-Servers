<?php

namespace SquadMS\Servers\Admin\Http\Livewire\Servers;

use Illuminate\Support\Facades\Config;
use SquadMS\Foundation\Admin\Http\Livewire\Contracts\AbstractModalComponent;

class CreateServer extends AbstractModalComponent
{
    public array $server = [
        'account_playtime' => false,
        'host'             => '127.0.0.1',
        'game_port'        => 7787,
        'query_port'       => 27165,
    ];

    protected $rules = [
        'server.name' => 'required|string|unique:SquadMS\Servers\Models\Server,name',

        'server.account_playtime' => 'required|boolean',

        'server.host' => 'required|ipv4',
        'server.game_port' => 'required|integer|min:1|max:65535',
        'server.query_port' => 'required|integer|min:1|max:65535',

        'server.rcon_port' => 'required_with:rcon_password|integer|min:1|max:65535',
        'server.rcon_password' => 'required_with:rcon_port|string',
    ];

    public function createServer() {
        /* Validate the data first */
        $this->validate();

        /* Create the Server */
        (Config::get('sqms-servers.models.server'))::create($this->server);

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