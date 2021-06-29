<div class="card">
    <style>
        #chat-messages {
            display: grid;
            grid-template-columns: fit-content(100%) fit-content(100%) 4fr;
            grid-template-rows: 1fr;
            gap: 0 0;
            grid-template-areas: ". .";
        }

        .chat-scroll.loading {

        }
    </style>

    <div class="card-body">
        <h5 class="card-title">Server Chat</h5>

        <hr>

        <div 
            class="position-relative"
            x-data="{
                scrollLock: @entangle('scrollLock'),
                hasOld: @entangle('hasOld'),
                hasNew: @entangle('hasNew'),
                loadingOld: false,
                loadingNew: false,
            }"
            x-init="() => {

                debugger

                const scrollArea = $el.firstElementChild
                const scrollContent = document.getElementById('chat-messages')

                // Start scrolled down
                scrollArea.scrollTop = scrollArea.scrollHeight

                // Keep scrolled down if locked
                @this.on('loaded-new', (foo, bar) => {
                    if (scrollLock) {
                        scrollArea.scrollTop = scrollArea.scrollHeight
                    }
                })

                // Listen for scroll and toggle the scrollLock
                let lastScrollTop = scrollArea.scrollTop;
                scrollArea.addEventListener('scroll', e => {
                    const scrollPc = Math.ceil( (scrollArea.scrollTop / (scrollArea.scrollHeight - scrollArea.offsetHeight)) * 100 )

                    const direction = lastScrollTop > scrollArea.scrollTop
                    lastScrollTop = scrollArea.scrollTop;

                    if (direction && scrollPc <= 20 && hasOld) {
                        if (!loadingOld) {
                            loadingOld = true

                            const prevFirstChild = scrollContent.firstElementChild
                            @this.call('loadOld').finally(() => {
                                scrollArea.scrollTop += prevFirstChild.firstElementChild.offsetTop

                                loadingOld = false
                            })
                            
                        }
                    } else if (!direction && scrollPc >= 80 && hasNew) {
                        if (!loadingNew) {
                            loadingNew = true
                            @this.call('loadNew').finally(() => {
                                loadingNew = false
                            })
                        }
                    }
                    
                    if (scrollPc === 100 && !hasNew) {
                        scrollLock = true;
                    } else {
                        scrollLock = false;
                    }
                })
            }"
        >
            <div class="chat-scroll vh-100 position-relative overflow-auto bg-dark text-white mb-3" style="max-height: 60vh; transform: translateZ(0);">
                <div class="position-fixed w-100 h-100 d-flex justify-content-center align-items-center bg-transparent-dark-500">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading Chat-Messages...</span>
                    </div>
                </div>

                <div x-show="hasOld" id="chatLoadingOld" class="align-items-center justify-content-center p-3">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading old Chat-Messages...</span>
                    </div>
                </div>

                <div id="chat-messages">
                    @foreach ($messages as $message)
                        <div class="d-contents" id="message-{{ $message->id }}">
                            <div id="message-time-{{ $message->id }}" class="p-1 p-md-2 border border-grey">
                                {{ $message->time_short }}
                            </div>
                            <div id="message-type-{{ $message->id }}" class="p-1 p-md-2 border border-grey">
                                {{ $message->type_formatted }}
                            </div>
                            <div id="message-content-{{ $message->id }}" class="text-start flex-fill p-1 p-md-2 border border-grey {{ $message->color_class }}">
                                @if (!in_array($message->type, ['Camera', 'Warning', 'Kick', 'Ban']))
                                    @if ($message->user)
                                    <a href="{{ $message->user->profile_url }}" target="_BLANK">{{ $message->type !== 'Broadcast' ? $message->user->name : $message->name }}</a>:&nbsp;
                                    @else
                                    {{ $message->name }}:&nbsp;
                                    @endif
                                @endif
                                {{ $message->content }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div x-show="hasNew" id="chatLoadingNew" class="align-items-center justify-content-center p-3">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading new Chat-Messages...</span>
                    </div>
                </div>
            </div>

            <button x-show=" !scrollLock " role="button" class="btn btn-primary position-absolute me-3 mb-2" style="bottom: 0; right: 0" wire:click="lockScroll" wire:loading.attr="disabled">
                <i class="bi bi-arrow-down-square-fill"></i>
            </button>
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