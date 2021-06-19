<div>
    @if ($servers->count())
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Server</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($servers as $server)
                    <tr>
                        <td scope="row">{{ $server->name }}</td>
                        <td class="text-end">
                            <a href="{{ route(Config::get('sqms-servers.routes.def.servers.admin-server', ['server' => $server])) }}" class="btn btn-primary">Admin</a>
                            <x-sqms-foundation::button class="btn-warning" wire:click="showEditServer({{ $server->id }})" wire:loading.attr="disabled">
                                Edit
                            </x-sqms-foundation::button>
                            <x-sqms-foundation::button class="btn-danger" wire:click="showDeleteServer({{ $server->id }})" wire:loading.attr="disabled">
                                Delete
                            </x-sqms-foundation::button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Edit Modal -->
        <x-sqms-foundation::dialog-modal model="showEditModal" maxWidth="xl" fullscreen="xl" class="text-start">
            <x-slot name="title">
                Edit Server
            </x-slot>
        
            <x-slot name="content">
                @if ($selectedServer)
                    <x-sqms-foundation::form-input class="mb-3" type="text" name="selectedServer.name" placeholder="New server name" label="Server name" wire:dirty.class="border-warning" wire:model.lazy="selectedServer.name" />

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" {{ \Illuminate\Support\Arr::get($selectedServer, 'account_playtime', false) ? 'checked' : '' }} wire:dirty.class="border-warning" wire:model.lazy="selectedServer.account_playtime" value="1">
                        <label class="form-check-label" for="flexSwitchCheckDefault">Account playtime</label>
                    </div>

                    <x-sqms-foundation::form-input class="mb-3" type="text" name="selectedServer.host" placeholder="New server host" label="Server host" wire:dirty.class="border-warning" wire:model.lazy="selectedServer.host" />

                    <x-sqms-foundation::form-input class="mb-3" type="text" name="selectedServer.game_port" placeholder="New server port" label="Server port" wire:dirty.class="border-warning" wire:model.lazy="selectedServer.game_port" />

                    <x-sqms-foundation::form-input class="mb-3" type="text" name="selectedServer.query_port" placeholder="New server query port" label="Server query port" wire:dirty.class="border-warning" wire:model.lazy="selectedServer.query_port" />

                    <x-sqms-foundation::form-input class="mb-3" type="password" name="selectedServer.rcon_password" placeholder="New server RCON password" label="Server RCON password" wire:dirty.class="border-warning" wire:model.lazy="selectedServer.rcon_password" />

                    <x-sqms-foundation::form-input class="mb-3" type="text" name="selectedServer.rcon_port" placeholder="New server RCON port" label="Server RCON port" wire:dirty.class="border-warning" wire:model.lazy="selectedServer.rcon_port" />
                @else
                    <p class="lead color-warning">No Server selected for editing.</p>
                @endif
            </x-slot>
        
            <x-slot name="footer">
                <x-sqms-foundation::button class="btn-dark" wire:click="$set('showEditModal', false)" wire:loading.attr="disabled">
                    Close
                </x-sqms-foundation::button>
        
                <div class="flex-grow-1"></div>

                <x-sqms-foundation::button class="btn-success" wire:click="editServer" wire:loading.attr="disabled">
                    Update
                </x-sqms-foundation::button>
            </x-slot>
        </x-sqms-foundation::dialog-modal>

        <!-- Delete Modal -->
        <x-sqms-foundation::confirm-modal model="showDeleteModal" class="text-start">
            <x-slot name="title">
                Delete Server
            </x-slot>
        
            <x-slot name="content">
                <p>Are you sure that you want to delete the Server?</p>
            </x-slot>
        
            <x-slot name="footer">
                <x-sqms-foundation::button class="btn-dark" wire:click="$set('showDeleteModal', false)" wire:loading.attr="disabled">
                    Cancel
                </x-sqms-foundation::button>
        
                <div class="flex-grow-1"></div>

                <x-sqms-foundation::button class="btn-danger" wire:click="deleteServer" wire:loading.attr="disabled">
                    Delete
                </x-sqms-foundation::button>
            </x-slot>
        </x-sqms-foundation::confirm-modal>
    </div>

    {{ $servers->links() }}
    @else
    <p class="text-center">No servers have been created yet.</p>
    @endif
</div>