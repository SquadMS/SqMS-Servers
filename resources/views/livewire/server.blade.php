<div wire:poll.60s>
    <section class="sqmss-bg-gray-100 bg-map-no-map {{ $server->online && $server->last_query_result->level() ? 'bg-map-' . \SquadMS\Foundation\Helpers\LevelHelper::levelToClass($server->last_query_result->level()) : '' }} sqmss-bg-cover sqmss-bg-center" server-level-bg="{{ $server->online && $server->last_query_result->level() ? 'bg-map-' . \SquadMS\Foundation\Helpers\LevelHelper::levelToClass($server->last_query_result->level()) : '' }}">
        <div class="sqmss-container sqmss-mx-auto sm:sqmss-px-4">
            <div class="sqmss-flex sqmss-flex-wrap sqmss-min-vh-50 sqmss-justify-center sqmss-items-center sqmss-p-12">
                @foreach (range(1, 2) as $teamId)
                    @if ($server->online)
                        @php
                            $bgFactionClass = count($server->last_query_result->teamTags()) === 2 ? 'bg-faction-' . $server->last_query_result->teamTags()[$teamId] : null;
                        @endphp
                        <div class="lg:sqmss-p-6 sqmss-flex sqmss-justify-center sqmss-items-center">
                            <div class="sqmss-aspect-squad-flag sqmss-h-40 sqmss-relative flag {{ $bgFactionClass }} sqmss-bg-cover sqmss-bg-center" flag-class="{{ $bgFactionClass }}" team-id="{{ $teamId }}">
                                <div class="sqmss-bg-gradient-to-b sqmss-from-transparent sqmss-to-black sqmss-opacity-30 {{ $loop->first ? '' : 'sqmss-right' }} sqmss-absolute sqmss-top-0 sqmss-w-full sqmss-h-full"></div>
                            </div>
                        </div>
                    @endif

                    @if ($loop->first)
                        <div class="sqmss-w-full lg:sqmss-w-auto sqmss-flex sqmss-justify-center sqmss-items-center">
                            <span class="sqmss-text-blue-600 sqmss-h2">VS.</span>
                        </div>
                    @endif
                @endforeach

                @if (! $server->online)
                    <div class="sqmss-relative sqmss-flex-grow sqmss-max-w-full sqmss-flex-1 sqmss-px-4">
                        <p class="sqmss-h3 sqmss-text-center">Server offline :(</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <section class="sqmss-my-6">
        <div class="sqmss-container sqmss-mx-auto sm:sqmss-px-4">
            <!-- Server name -->
            <div class="sqmss-flex sqmss-flex-wrap sqmss-mb-5">
                <div class="sqmss-relative sqmss-flex-grow sqmss-max-w-full sqmss-flex-1 sqmss-px-4">
                    <h1 class="sqmss-text-center sqmss-truncate sqmss-text-2xl">{{ $server->last_query_result->name() }}</h1>
                    <p class="sqmss-text-xl sqmss-font-light sqmss-text-center">
                        @if ($server->online)
                            <span>{{ $server->last_query_result->count() }}(+{{ $server->last_query_result->queue() }})/{{ $server->last_query_result->slots() }}(+{{ $server->last_query_result->reserved() }}) {{ __('sqms-servers::pages/servers.server.players') }}</span>
                        @else
                            <span class="sqmss-text-red-600 sqmss-truncate">{{ __('sqms-servers::pages/servers.server.offline') }}</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Population -->
            <x-sqms-servers::player-list.population :server="$server" />
        </div>
    </section>
</div>
@pushOnce('styles')
@livewireStyles
<link href="{{ mix('css/sqms-servers.css', 'vendor/sqms-servers') }}" rel="stylesheet">
@endPushOnce

@pushOnce('scripts')
@livewireScripts
@endPushOnce
