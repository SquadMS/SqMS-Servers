@extends('sqms-foundation::structure.layout', [
    'mainClass' => 'server',
    'mainAttributes' => 'server-id="' . $server->id . '"'
])

@section('content')
<section class="bg-light bg-map-no-map {{ $server->online && $server->last_query_result->level() ? 'bg-map-' . \SquadMS\Foundation\Helpers\LevelHelper::levelToClass($server->last_query_result->level()) : '' }} bg-cover bg-center" server-level-bg="{{ $server->online && $server->last_query_result->level() ? 'bg-map-' . \SquadMS\Foundation\Helpers\LevelHelper::levelToClass($server->last_query_result->level()) : '' }}">
    <div class="container">
        <div class="row min-vh-50 align-items-center p-5">
            @foreach (range(1, 2) as $teamId)
                @php
                    $bgFactionClass = count($server->last_query_result->teamTags()) === 2 ? 'bg-faction-' . $server->last_query_result->teamTags()[$teamId] : null;
                @endphp
                <div class="col-12 col-md data-show-online {{ $server->online  ? '' : 'd-none' }}">
                    <div class="squad-flag p-md-4 d-flex justify-content-center align-items-center">
                        <div class="ratio ratio-squad-flag data-team-tags flag {{ $bgFactionClass }} bg-cover bg-center" flag-class="{{ $bgFactionClass }}" team-id="{{ $teamId }}">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="gradient {{ $loop->first ? '' : 'right' }} position-absolute w-100 h-100"></div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($loop->first)
                    <div class="col-12 col-md-auto d-flex justify-content-center align-items-center">
                        <span class="text-primary h2">VS.</span>
                    </div>
                @endif
            @endforeach

            <div class="col data-show-offline {{ $server->online  ? 'd-none' : '' }}">
                <p class="h3 text-center">Server offline :(</p>
            </div>
        </div>
    </div>
</section>
<section class="my-6">
    <div class="container">
        <!-- Server name -->
        <div class="row mb-5">
            <div class="col">
                <h1 class="text-center text-truncate data-server-name">{{ $server->last_query_result->name() }}</h1>
                <p class="lead text-center">
                    <span class="data-show-online {{ $server->online  ? '' : 'd-none' }}"><span class="data-count">{{ $server->last_query_result->count() }}</span>(+<span class="data-queue">{{ $server->last_query_result->queue() }}</span>)/<span class="data-slots">{{ $server->last_query_result->slots() }}</span>(+<span class="data-reserved">{{ $server->last_query_result->reserved() }}</span>) {{ __('sqms-default-theme::pages/servers.server.players') }}</span>
                    <span class="text-danger text-truncate data-show-offline {{ $server->online  ? 'd-none' : '' }}">{{ __('sqms-default-theme::pages/servers.server.offline') }}</span>
                </p>
            </div>
        </div>

        <!-- Population -->
        <x-sqms-default-theme::player-list.population :server="$server" />
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/morphdom@2.6.1/dist/morphdom.min.js"></script>
<script src="{{ mix('js/server-status-listener.js', 'themes/sqms-server') }}"></script>
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
            teamTags: [
                'data-team-tags',
                function(element, value) {
                    if (element.classList.contains('flag')) {
                        const oldFlag = element.getAttribute('flag-class');
                        const teamId = element.getAttribute('team-id');
                        const newClass = `bg-faction-${value[teamId]}`;
                        /* Remove old class and add new one */
                        element.classList.remove(oldFlag);
                        element.classList.add(newClass);
                        /* Set server-level-bg attribute properly */
                        element.setAttribute('flag-class', newClass);
                    }
                },
            ]
        }, function (server, event) {
            const playerLists = server.getElementsByClassName('data-player-list');
            
            if (playerLists.length) {
                fetch(`${window.location.origin}/servers/${server.getAttribute('server-id')}/population`)
                .then(async response => {
                    if (response.ok) {
                        for (const playerList of playerLists) {
                            if (typeof morphdom === 'function') {
                                morphdom(playerList, await response.text())
                            } else {
                                playerList.innerHTML = await response.text();
                            }                            
                        }
                    }
                });
            }
        });
    });
</script>
@endpush
