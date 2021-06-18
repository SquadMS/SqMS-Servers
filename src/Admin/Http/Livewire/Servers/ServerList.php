<?php

namespace SquadMS\Servers\Admin\Http\Livewire\Servers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use SquadMS\Servers\Models\Server;
use SquadMS\Servers\Repositories\ServerRepositoriy;

class ServerList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'server:created' => '$refresh',
        'server:deleted' => '$refresh',
    ];

    protected $rules = [
        'selectedServer.*' => null, // TODO: Remove this somehow...
    ];

    public bool $showEditModal = false;
    public bool $showDeleteModal = false;
    public ?Server $selectedServer = null;

    public function showEditServer(Server $server)
    {
        /* Set the selected server */
        $this->selectedServer = $server;

        /* Display the edit modal */
        $this->showEditModal = true;
    }

    public function editServer()
    {
        /* Authorize the action */
        $this->authorize('update', $this->selectedServer);

        /* Validate the data first */
        $this->validate();

        /* Create the Server */
        $this->selectedServer->save();

        $this->showEditModal = false;

        /* Emit event */
        $this->emitUp('server:updated');
    }

    public function showDeleteServer(Server $server)
    {
        /* Set the selected server */
        $this->selectedServer = $server;

        /* Display the edit modal */
        $this->showDeleteModal = true;
    }

    public function deleteServer()
    {
        /* Authorize the action */
        $this->authorize('delete', $this->selectedServer);

        /* Delete the Server */
        $this->selectedServer->delete();

        /* Hide the modal (backdrop) */
        $this->showDeleteModal = false;

        /* Emit event */
        $this->emit('server:deleted');
    }

    public function render()
    {
        return View::make('sqms-servers::admin.livewire.servers.server-list', [
            'servers' => ServerRepositoriy::getModelQuery()->paginate(10),
        ]);
    }

    public function rules()
    {
        return [
            'selectedServer.name' => 'required|string|unique:SquadMS\Servers\Models\Server,name,'.$this->server->id,

            'selectedServer.account_playtime' => 'required|boolean',

            'selectedServer.host'      => 'required|ipv4',
            'selectedServer.game_port' => [
                'required',
                'integer',
                'min:1',
                'max:65535',
                Rule::unique('servers', 'game_port')->ignore($this->server->id)->where('host', Arr::get($this->server, 'host')),
            ],
            'selectedServer.query_port' => [
                'required',
                'integer',
                'min:1',
                'max:65535',
                Rule::unique('servers', 'query_port')->ignore($this->server->id)->where('host', Arr::get($this->server, 'host')),
            ],

            'selectedServer.rcon_port' => [
                'nullable',
                'required_with:selectedServer.rcon_password',
                'integer',
                'min:1',
                'max:65535',
                Rule::unique('servers', 'rcon_port')->ignore($this->server->id)->where('host', Arr::get($this->server, 'host')),
            ],
            'selectedServer.rcon_password' => 'nullable|required_with:selectedServer.rcon_port|string',
        ];
    }
}
