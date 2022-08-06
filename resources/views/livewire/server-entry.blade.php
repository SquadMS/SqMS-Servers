<a href="{{ route(Config::get('sqms-servers.routes.def.server.name'), ['server' => $server]) }}" class="sqmss-w-full sqmss-mb-4" wire:poll.60s.visible>
    <div class="server sqmss-bg-gray-100 sqmss-bg-cover sqmss-bg-center bg-map-no-map {{ $bgClass }}">
        <div class="server-inner sqmss-flex sqmss-flex-col md:sqmss-flex-row">
            <div class="md:sqmss-flex-grow sqmss-min-width-0 fsqmss-lex sqmss-items-center sqmss-p-6">
                @if ($server->last_query_result->online())
                    <span class="sqmss-w-full sqmss-h3 sqmss-truncate sqmss-text-center sqmss-text-md-start sqmss-text-white md:sqmss-mb-0">{{ $server->last_query_result->name() }}</span>
                @else
                    <span class="sqmss-w-full sqmss-h3 sqmss-truncate sqmss-text-center sqmss-text-md-start sqmss-text-gray-600 md:sqmss-mb-0">{{ $server->last_query_result->name() }}</span>
                @endif
            </div>

            <div class="sqmss-flex-grow sqmss-min-width-0 md:sqmss-flex-grow-0 sqmss-flex sqmss-h-full sqmss-items-center sqmss-justify-center sqmss-p-2 md:sqmss-px-6 extra">
                @if ($server->last_query_result->online())
                    <span class="sqmss-text-white">{{ $server->last_query_result->count() }}(+{{ $server->last_query_result->queue() }})/{{ $server->last_query_result->slots() }}(+{{ $server->last_query_result->reserved() }}) {{ __('sqms-servers::pages/servers.server.players') }}</span>
                @else
                    <span class="sqmss-text-red-600 sqmss-truncate">{{ __('sqms-servers::pages/servers.server.offline') }}</span>
                @endif
            </div>
        </div>
    </div>
</a>