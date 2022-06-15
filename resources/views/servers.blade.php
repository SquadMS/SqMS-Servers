@extends('sqms-foundation::templates.page')

@section('title')
    {{ __('sqms-servers::pages/servers.heading') }}
@endsection

@section('page-content')
<div class="flex flex-wrap  server-list">
    @foreach ($servers as $server)
        <div class="w-full mb-4">
            <div class="server bg-gray-100 bg-map-no-map {{ $server->last_query_result->online() && $server->last_query_result->level() ? 'bg-map-' . \SquadMS\Foundation\Helpers\LevelHelper::levelToClass($server->last_query_result->level()) : '' }} bg-cover bg-center" server-id="{{ $server->id }}" server-level-bg="{{ $server->last_query_result->online() && $server->last_query_result->level() ? 'bg-map-' . \SquadMS\Foundation\Helpers\LevelHelper::levelToClass($server->last_query_result->level()) : '' }}">
                <div class="server-inner flex flex-col md:flex-row">
                    <div class="main-info flex flex-col md:flex-row md:items-center md:flex-grow min-width-0">
                        <div class="md:flex-grow min-width-0 flex items-center p-6">
                            <span class="w-full h3 truncate text-center text-md-start text-white md:mb-0 data-server-name data-show-online {{ $server->last_query_result->online() ? '' : 'hidden' }}">{{ $server->last_query_result->name() }}</span>
                            <span class="w-full h3 truncate text-center text-md-start text-gray-600 md:mb-0 data-show-offline {{ $server->last_query_result->online()  ? 'hidden' : '' }}">{{ $server->last_query_result->name() }}</span>
                        </div>

                        <div class="flex-grow min-width-0 md:flex-grow-0 flex h-full items-center justify-center p-2 md:px-6 extra">
                            <span class="text-white data-show-online {{ $server->last_query_result->online()  ? '' : 'hidden' }}"><span class="data-count">{{ $server->last_query_result->count() }}</span>(+<span class="data-queue">{{ $server->last_query_result->queue() }}</span>)/<span class="data-slots">{{ $server->last_query_result->slots() }}</span>(+<span class="data-reserved">{{ $server->last_query_result->reserved() }}</span>) {{ __('sqms-servers::pages/servers.server.players') }}</span>
                            <span class="text-red-600 truncate data-show-offline {{ $server->last_query_result->online()  ? 'hidden' : '' }}">{{ __('sqms-servers::pages/servers.server.offline') }}</span>
                        </div>
                    </div>

                    <div class="actions flex flex-row items-stretch">
                        <a href="{{ $server->connect_url }}" class="extra flex flex-grow md:flex-grow-0 items-center justify-center px-3 py-2 md:px-6 md:py-1 text-decoration-none">
                            <span class="text-steam lh-1"><i class="bi bi-controller"></i></span>
                        </a>

                        <a href="{{ route(Config::get('sqms-servers.routes.def.server.name'), ['server' => $server]) }}" class="extra flex flex-grow md:flex-grow-0 items-center justify-center px-3 py-2 md:px-6 md:py-1 text-decoration-none">
                            <span class="text-blue-600 lh-1"><i class="bi bi-info-circle-fill"></i></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script src="{{ mix('js/server-status-listener.js', 'themes/sqms-servers') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        /* Initialize and listen for server status updates */
        const listener = new window.ServerStatusListener({
            levelClass: [
                'data-level-class',
                function(element, value) {
                    const oldClass = element.getAttribute('server-level-bg');
                    const newClass = `bg-map-${value}`;
                    
                    /* Remove old class and add new one */
                    element.classList.remove(oldClass);
                    element.classList.add(newClass);
                    /* Set server-level-bg attribute properly */
                    element.setAttribute('server-level-bg', newClass);
                },
            ],
        });
    });
</script>
@endpush
