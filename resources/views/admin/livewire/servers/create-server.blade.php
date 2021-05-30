<div class="d-inline-block text-start">
    <x-sqms-foundation::button class="btn-primary" wire:click="$toggle('showModal')" wire:loading.attr="disabled">
        Create
    </x-sqms-foundation::button>

    <x-sqms-foundation::dialog-modal model="showModal" class="fs-base">
        <x-slot name="title">
            Create Server
        </x-slot>
    
        <x-slot name="content">
            <x-sqms-foundation::form-input class="mb-3" type="text" name="server.name" placeholder="New server name" label="Server name" wire:dirty.class="border-warning" wire:model.lazy="server.name" />

            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" {{ $server->account_playtime ? 'checked' : '' }} wire:dirty.class="border-warning" wire:model.lazy="server.account_playtime" value="1">
                <label class="form-check-label" for="flexSwitchCheckDefault">Account playtime</label>
            </div>

            <x-sqms-foundation::form-input class="mb-3" type="text" name="server.host" placeholder="New server host" label="Server host" wire:dirty.class="border-warning" wire:model.lazy="server.host" />

            <x-sqms-foundation::form-input class="mb-3" type="text" name="server.port" placeholder="New server port" label="Server port" wire:dirty.class="border-warning" wire:model.lazy="server.game_port" />

            <x-sqms-foundation::form-input class="mb-3" type="text" name="server.query_port" placeholder="New server query port" label="Server query port" wire:dirty.class="border-warning" wire:model.lazy="server.query_port" />

            <x-sqms-foundation::form-input class="mb-3" type="text" name="server.rcon_password" placeholder="New server RCON password" label="Server RCON password" wire:dirty.class="border-warning" wire:model.lazy="server.rcon_password" />

            <x-sqms-foundation::form-input class="mb-3" type="text" name="server.rcon_port" placeholder="New server RCON port" label="Server RCON port" wire:dirty.class="border-warning" wire:model.lazy="server.rcon_port" />

        </x-slot>
    
        <x-slot name="footer">
            <x-sqms-foundation::button class="btn-dark" wire:click="$set('showModal', false)" wire:loading.attr="disabled">
                Cancel
            </x-sqms-foundation::button>
    
            <div class="flex-grow-1"></div>

            <x-sqms-foundation::button class="btn-success" wire:click="createServer" wire:loading.attr="disabled">
                Create
            </x-sqms-foundation::button>
        </x-slot>
    </x-sqms-foundation::dialog-modal>
</div>