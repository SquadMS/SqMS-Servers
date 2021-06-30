<div class="card">
    <style>
        .chat-messages {
            display: grid;
            grid-template-columns: fit-content(100%) fit-content(100%) 4fr;
            grid-template-rows: 1fr;
            gap: 0 0;
            grid-template-areas: ". .";
            max-height: 60vh;

        }
    </style>

    <div class="card-body">
        <h5 class="card-title">Server Chat</h5>

        <hr>

        <div
            class="position-relative bg-dark text-white mb-3"
            x-data="{
                scrollLock: @entangle('scrollLock').defer,
                hasOld: @entangle('hasOld'),
                hasNew: @entangle('hasNew'),
                loadingOld: false,
                loadingNew: false,
            }"
            x-init="() => {
                const scrollArea = $el.firstElementChild

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

                            const prevFirstChild = scrollArea.firstElementChild
                            @this.call('loadOld').finally(() => {
                                if (prevFirstChild !== scrollArea.firstElementChild) {
                                    scrollArea.scrollTop += prevFirstChild.firstElementChild.offsetTop
                                }
                                
                                loadingOld = false
                            })
                            
                        }
                    } else if (!direction && scrollPc >= 80 && hasNew) {
                        if (!loadingNew) {
                            loadingNew = true

                            const prevLastChild = scrollArea.lastElementChild
                            @this.call('loadNew').finally(() => {
                                if (prevLastChild !== scrollArea.lastElementChild) {
                                    scrollArea.scrollTop -= prevLastChild.firstElementChild.offsetTop
                                }

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
            <div class="chat-messages vh-100 overflow-auto">
                @foreach ($messages as $message)
                    <div id="message-{{ $message->id }}" class="d-contents">
                        <div class="p-1 p-md-2 border border-grey">
                            {{ $message->time_short }}
                        </div>
                        <div class="p-1 p-md-2 border border-grey">
                            {{ $message->type_formatted }}
                        </div>
                        <div class="text-start flex-fill p-1 p-md-2 border border-grey {{ $message->color_class }}">
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

            <div class="position-absolute w-100 h-100 d-none justify-content-center align-items-center bg-transparent-dark-500" style="z-index:1;top:0" wire:loading.class.remove="d-none" wire:loading.class="d-flex">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading Chat-Messages...</span>
                </div>
            </div>

            <button x-show=" !scrollLock " role="button" class="btn btn-primary position-absolute me-4 mb-2" style="bottom: 0; right: 0" wire:click="lockScroll" wire:loading.attr="disabled">
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