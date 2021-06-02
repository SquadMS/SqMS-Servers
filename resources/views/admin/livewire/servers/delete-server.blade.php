<div class="d-inline-block text-start">
    <x-sqms-foundation::button class="btn-danger" wire:click="$toggle('showModal')" wire:loading.attr="disabled">
        Delete
    </x-sqms-foundation::button>

    <x-sqms-foundation::confirm-modal model="showModal">
        <x-slot name="title">
            Delete Server
        </x-slot>
    
        <x-slot name="content">
            <p>Are you sure that you want to delete the Server?</p>
        </x-slot>
    
        <x-slot name="footer">
            <x-sqms-foundation::button class="btn-dark" wire:click="$set('showModal', false)" wire:loading.attr="disabled">
                Cancel
            </x-sqms-foundation::button>
    
            <div class="flex-grow-1"></div>

            <x-sqms-foundation::button class="btn-danger" wire:click="deleteServer" wire:loading.attr="disabled">
                Delete
            </x-sqms-foundation::button>
        </x-slot>
    </x-sqms-foundation::confirm-modal>
</div>