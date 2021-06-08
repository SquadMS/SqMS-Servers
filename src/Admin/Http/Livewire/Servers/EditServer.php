<?php

namespace SquadMS\Servers\Admin\Http\Livewire\Servers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use SquadMS\Foundation\Admin\Http\Livewire\Contracts\AbstractModalComponent;
use SquadMS\Servers\Models\Server;

class EditServer extends AbstractModalComponent
{
    use AuthorizesRequests;

    public Server $server;

    protected $rules = [
        'server.*' => null, // TODO: Remove this somehow...
    ];

    public function rules()
    {
        return [
            'server.name' => 'required|string|unique:SquadMS\Servers\Models\Server,name,' . $this->server->id,
    
            'server.account_playtime' => 'required|boolean',
    
            'server.host' => 'required|ipv4',
            'server.game_port' => [
                'required',
                'integer',
                'min:1',
                'max:65535',
                Rule::unique('servers', 'game_port')->ignore($this->server->id)->where('host', Arr::get($this->server, 'host'))
            ],
            'server.query_port' => [
                'required',
                'integer',
                'min:1',
                'max:65535',
                Rule::unique('servers', 'query_port')->ignore($this->server->id)->where('host', Arr::get($this->server, 'host'))
            ],
    
            'server.rcon_port' => [
                'nullable',
                'required_with:server.rcon_password',
                'integer',
                'min:1',
                'max:65535',
                Rule::unique('servers', 'rcon_port')->ignore($this->server->id)->where('host', Arr::get($this->server, 'host'))
            ],
            'server.rcon_password' => 'nullable|required_with:server.rcon_port|string',
        ];
    }

    public function updateServer() {
        /* Authorize the action */
        $this->authorize('update', $this->server);

        /* Validate the data first */
        $this->validate();
        
        /* Create the Server */
        $this->server->save();

        $this->hideModal();

        /* Emit event */
        $this->emitUp('server:updated');
    }

    public function mount(Server $server)
    {
        $this->title = $server->makeVisible([
            'rcon_port',
            'rcon_password',
        ]);
    }
    
    public function render()
    {
        return View::make('sqms-servers::admin.livewire.servers.edit-server');
    }
}