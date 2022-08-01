<x-sqms-foundation::templates.page :title="__('sqms-servers::pages/servers.heading')">
<div class="sqmss-flex sqmss-flex-wrap server-list">
    @foreach ($servers as $server)
        <div class="sqmss-w-full sqmss-mb-4">
            <div class="server sqmss-bg-gray-100 bg-map-no-map {{ $server->last_query_result->online() && $server->last_query_result->level() ? 'bg-map-' . \SquadMS\Foundation\Helpers\LevelHelper::levelToClass($server->last_query_result->level()) : '' }} bg-cover bg-center" server-id="{{ $server->id }}" server-level-bg="{{ $server->last_query_result->online() && $server->last_query_result->level() ? 'bg-map-' . \SquadMS\Foundation\Helpers\LevelHelper::levelToClass($server->last_query_result->level()) : '' }}">
                <div class="server-inner sqmss-flex sqmss-flex-col md:sqmss-flex-row">
                    <div class="main-info sqmss-flex sqmss-flex-col md:sqmss-flex-row md:sqmss-items-center md:sqmss-flex-grow sqmss-min-width-0">
                        <div class="md:sqmss-flex-grow sqmss-min-width-0 fsqmss-lex sqmss-items-center sqmss-p-6">
                            <span class="sqmss-w-full sqmss-h3 sqmss-truncate sqmss-text-center sqmss-text-md-start sqmss-text-white md:sqmss-mb-0 data-server-name data-show-online {{ $server->last_query_result->online() ? '' : 'hidden' }}">{{ $server->last_query_result->name() }}</span>
                            <span class="sqmss-w-full sqmss-h3 sqmss-truncate sqmss-text-center sqmss-text-md-start sqmss-text-gray-600 md:sqmss-mb-0 data-show-offline {{ $server->last_query_result->online()  ? 'hidden' : '' }}">{{ $server->last_query_result->name() }}</span>
                        </div>

                        <div class="sqmss-flex-grow sqmss-min-width-0 md:sqmss-flex-grow-0 sqmss-flex sqmss-h-full sqmss-items-center sqmss-justify-center sqmss-p-2 md:sqmss-px-6 extra">
                            <span class="sqmss-text-white data-show-online {{ $server->last_query_result->online()  ? '' : 'hidden' }}"><span class="data-count">{{ $server->last_query_result->count() }}</span>(+<span class="data-queue">{{ $server->last_query_result->queue() }}</span>)/<span class="data-slots">{{ $server->last_query_result->slots() }}</span>(+<span class="data-reserved">{{ $server->last_query_result->reserved() }}</span>) {{ __('sqms-servers::pages/servers.server.players') }}</span>
                            <span class="sqmss-text-red-600 sqmss-truncate data-show-offline {{ $server->last_query_result->online()  ? 'hidden' : '' }}">{{ __('sqms-servers::pages/servers.server.offline') }}</span>
                        </div>
                    </div>

                    <div class="actions sqmss-flex sqmss-flex-row sqmss-items-stretch">
                        <a href="{{ $server->connect_url }}" class="extra sqmss-flex sqmss-flex-grow md:sqmss-flex-grow-0 sqmss-items-center sqmss-justify-center sqmss-px-3 sqmss-py-2 md:sqmss-px-6 md:sqmss-py-1 sqmss-text-decoration-none">
                            <span class="sqmss-text-steam sqmss-lh-1"><i class="bi bi-controller"></i></span>
                        </a>

                        <a href="{{ route(Config::get('sqms-servers.routes.def.server.name'), ['server' => $server]) }}" class="extra sqmss-flex sqmss-flex-grow md:sqmss-flex-grow-0 sqmss-items-center sqmss-justify-center sqmss-px-3 sqmss-py-2 md:sqmss-px-6 md:sqmss-py-1 sqmss-text-decoration-none">
                            <span class="sqmss-text-blue-600 sqmss-lh-1"><i class="bi bi-info-circle-fill"></i></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@pushOnce('styles')
@livewireStyles
<link href="{{ mix('css/sqms-servers.css', 'vendor/sqms-servers') }}" rel="stylesheet">
@endPushOnce

@pushOnce('scripts')
@livewireScripts
@endPushOnce
</x-sqms-foundation::templates.page>