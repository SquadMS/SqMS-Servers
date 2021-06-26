<div class="card">
    <div class="card-body">
        <h5 class="card-title">Server Chat</h5>

        <hr>

        <div class="overflow-auto bg-dark text-white mb-3">
            <div id="chatLoadingBefore" class="align-items-center justify-content-center p-3 d-none">
                <div class="spinner-border" role="status" x-data="{
                    observe () {
                        let observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    @this.call('loadOld')
                                }
                            })
                        }, {
                            root: null
                        })
            
                        observer.observe(this.$el)
                    }
                }"
                x-init="observe">
                    <span class="sr-only">Loading more Chat-Messages...</span>
                </div>
            </div>
            <div class="chat-messages">
                @foreach ($messages as $message)
                    <div class="message-time p-1 p-md-2 border border-grey">
                        {{ $message->time->format('H:i') }}
                    </div>
                    <div class="message-type p-1 p-md-2 border border-grey">
                        {{ $message->type_formatted }}
                    </div>
                    <div class="message flex-fill p-1 p-md-2 border border-grey {{ $message->color_class }}">
                        @if (!in_array($message->type, ['Camera', 'Warning', 'Kick', 'Ban']))
                            @if ($message->user)
                            <a href="{{ $message->user->profile_url }}" target="_BLANK">{{ $message->user->name }}</a>:&nbsp;
                            @else
                            {{ $message->name }}:&nbsp;
                            @endif
                        @endif
                        {{ $message->content }}
                    </div>
                @endforeach
            </div>
        </div>

        @can ('admin servers moderation broadcast')
        <div class="mb-3">
            <label class="form-label">Send Broadcast</label>

            <div class="input-group">
                <input type="text" class="form-control" placeholder="Message to broadcast on the Server" aria-label="Message to broadcast on the Server" wire:model.lazy="message">
                <x-sqms-foundation::button class="btn-outline-primary" wire:click="sendMessage" wire:loading.attr="disabled">
                    Send
                </x-sqms-foundation::button>
            </div>
        </div>
        @endcan
    </div>
</div>