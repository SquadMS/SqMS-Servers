<div class="sqmss-w-full sqmss-mb-4" wire:poll.60s.visible>
    <div class="server sqmss-bg-gray-100 bg-map-no-map {{ $server->last_query_result->online() && $server->last_query_result->level() ? 'bg-map-' . \SquadMS\Foundation\Helpers\LevelHelper::levelToClass($server->last_query_result->level()) : '' }} sqmss-bg-cover sqmss-bg-center" server-id="{{ $server->id }}" server-level-bg="{{ $server->last_query_result->online() && $server->last_query_result->level() ? 'bg-map-' . \SquadMS\Foundation\Helpers\LevelHelper::levelToClass($server->last_query_result->level()) : '' }}">
        <div class="server-inner sqmss-flex sqmss-flex-col md:sqmss-flex-row">
            <div class="md:sqmss-flex-grow sqmss-min-width-0 fsqmss-lex sqmss-items-center sqmss-p-6">
                <a href="{{ route(Config::get('sqms-servers.routes.def.server.name'), ['server' => $server]) }}">
                    <span class="sqmss-w-full sqmss-h3 sqmss-truncate sqmss-text-center sqmss-text-md-start sqmss-text-white md:sqmss-mb-0 data-server-name data-show-online {{ $server->last_query_result->online() ? '' : 'hidden' }}">{{ $server->last_query_result->name() }}</span>
                    <span class="sqmss-w-full sqmss-h3 sqmss-truncate sqmss-text-center sqmss-text-md-start sqmss-text-gray-600 md:sqmss-mb-0 data-show-offline {{ $server->last_query_result->online()  ? 'hidden' : '' }}">{{ $server->last_query_result->name() }}</span>
                </a>
            </div>

            <div class="sqmss-flex-grow sqmss-min-width-0 md:sqmss-flex-grow-0 sqmss-flex sqmss-h-full sqmss-items-center sqmss-justify-center sqmss-p-2 md:sqmss-px-6 extra">
                <span class="sqmss-text-white data-show-online {{ $server->last_query_result->online()  ? '' : 'hidden' }}"><span class="data-count">{{ $server->last_query_result->count() }}</span>(+<span class="data-queue">{{ $server->last_query_result->queue() }}</span>)/<span class="data-slots">{{ $server->last_query_result->slots() }}</span>(+<span class="data-reserved">{{ $server->last_query_result->reserved() }}</span>) {{ __('sqms-servers::pages/servers.server.players') }}</span>
                <span class="sqmss-text-red-600 sqmss-truncate data-show-offline {{ $server->last_query_result->online()  ? 'hidden' : '' }}">{{ __('sqms-servers::pages/servers.server.offline') }}</span>
            </div>
        </div>
    </div>
</div>