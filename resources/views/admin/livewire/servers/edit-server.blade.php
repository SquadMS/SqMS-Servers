<div class="d-inline-block text-start">
    <x-sqms-foundation::button class="btn-warning" wire:click="$toggle('showModal')" wire:loading.attr="disabled">
        Edit
    </x-sqms-foundation::button>

    <x-sqms-foundation::dialog-modal wire:model="showModal" maxWidth="xl" fullscreen="xl">
        <x-slot name="title">
            Edit Server
        </x-slot>
    
        <x-slot name="content">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Server name</label>

                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Name of the server" aria-label="Name of the server" value="{{ $server->name }}" wire:model.lazy="server.name">
                    <x-sqms-foundation::button class="btn-outline-success" wire:click="updateServer" wire:loading.attr="disabled">
                        Update
                    </x-sqms-foundation::button>
                </div>
            </div>
        </x-slot>
    
        <x-slot name="footer">
            <x-sqms-foundation::button class="btn-dark" wire:click="$set('showModal', false)" wire:loading.attr="disabled">
                Close
            </x-sqms-foundation::button>
    
            <div class="flex-grow-1"></div>
        </x-slot>
    </x-sqms-foundation::dialog-modal>
</div>