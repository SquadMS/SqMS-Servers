<div class="card">
    <style>
        .chat-messages {
            display: grid;
            grid-template-columns: fit-content(100%) fit-content(100%) 4fr;
            grid-template-rows: 1fr;
            gap: 0 0;
            grid-template-areas: ". .";
        }
    </style>

    <div class="card-body">
        <h5 class="card-title">Server Chat</h5>

        <hr>

        <div class="chat-scroll vh-100 overflow-auto bg-dark text-white mb-3" style="max-height: 100vh">
            @if ($hasOld)
                <div 
                    id="chatLoadingBefore" 
                    class="align-items-center justify-content-center p-3" 
                    x-data="{
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

                            // Start scrolled down
                            this.$el.parentElement.scrollTop = this.$el.parentElement.scrollHeight;
                
                            // Check if element is scrolled in view
                            observer.observe(this.$el)
                        }
                    }"
                    x-init="observe"
                >
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading more Chat-Messages...</span>
                    </div>
                </div>
            @endif

            <div class="chat-messages">
                @foreach ($messages as $message)
                    <div id="message-time-{{ $message->id }}" class="p-1 p-md-2 border border-grey">
                        {{ $message->time->format('H:i') }}
                    </div>
                    <div id="message-type-{{ $message->id }}" class="p-1 p-md-2 border border-grey">
                        {{ $message->type_formatted }}
                    </div>
                    <div id="message-content-{{ $message->id }}" class="text-start flex-fill p-1 p-md-2 border border-grey {{ $message->color_class }}">
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