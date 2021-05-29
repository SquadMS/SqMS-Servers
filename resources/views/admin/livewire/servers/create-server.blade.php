<div class="d-inline-block text-start">
    <x-sqms-foundation::button class="btn-primary" wire:click="$toggle('showModal')" wire:loading.attr="disabled">
        Create
    </x-sqms-foundation::button>

    <x-sqms-foundation::dialog-modal wire:model="showModal" class="fs-base">
        <x-slot name="title">
            Create Server
        </x-slot>
    
        <x-slot name="content">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="New server name" aria-label="New server name" wire:model.lazy="server.name">
            </div>

            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" {{ $server->account_playtime ? 'checked' : '' }} wire:changed.lazy="server.account_playtime">
                <label class="form-check-label" for="flexSwitchCheckDefault">Account playtime</label>
            </div>

            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="New server host" aria-label="New server host" wire:model.lazy="server.host">
            </div>

            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="New server port" aria-label="New server port" wire:model.lazy="server.game_port">
            </div>

            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="New server query port" aria-label="New server query port" wire:model.lazy="server.query_port">
            </div>

            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="New server rcon password" aria-label="New server rcon password" wire:model.lazy="server.rcon_password">
            </div>

            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="New server rcon port" aria-label="New server rcon port" wire:model.lazy="server.rcon_port">
            </div>
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