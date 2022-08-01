<x-sqms-foundation::layouts.app :title="__('sqms-servers::pages/servers.heading')" :server-id="$server->id">
<section class="sqmss-bg-gray-100 bg-map-no-map {{ $server->online && $server->last_query_result->level() ? 'bg-map-' . \SquadMS\Foundation\Helpers\LevelHelper::levelToClass($server->last_query_result->level()) : '' }} bg-cover bg-center" server-level-bg="{{ $server->online && $server->last_query_result->level() ? 'bg-map-' . \SquadMS\Foundation\Helpers\LevelHelper::levelToClass($server->last_query_result->level()) : '' }}">
    <div class="sqmss-container sqmss-mx-auto sm:sqmss-px-4">
        <div class="sqmss-flex sqmss-flex-wrap  sqmss-min-vh-50 sqmss-items-center sqmss-p-12">
            @foreach (range(1, 2) as $teamId)
                @php
                    $bgFactionClass = count($server->last_query_result->teamTags()) === 2 ? 'bg-faction-' . $server->last_query_result->teamTags()[$teamId] : null;
                @endphp
                <div class="sqmss-w-full sqmss-relative md:sqmss-flex-grow md:sqmss-flex-1 data-show-online {{ $server->online  ? '' : 'sqmss-hidden' }}">
                    <div class="squad-flag md:sqmss-p-6 sqmss-flex sqmss-justify-center sqmss-items-center">
                        <div class="sqmss-aspect-squad-flag data-team-tags flag {{ $bgFactionClass }} sqmss-bg-cover sqmss-bg-center" flag-class="{{ $bgFactionClass }}" team-id="{{ $teamId }}">
                            <div class="flex justify-center items-center">
                                <div class="sqmss-gradient {{ $loop->first ? '' : 'sqmss-right' }} sqmss-absolute sqmss-w-full sqmss-h-full"></div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($loop->first)
                    <div class="sqmss-w-full sqmss-col-md-auto sqmss-flex sqmss-justify-center sqmss-items-center">
                        <span class="sqmss-text-blue-600 sqmss-h2">VS.</span>
                    </div>
                @endif
            @endforeach

            <div class="sqmss-relative sqmss-flex-grow sqmss-max-w-full sqmss-flex-1 sqmss-px-4 data-show-offline {{ $server->online  ? 'sqmss-hidden' : '' }}">
                <p class="sqmss-h3 sqmss-text-center">Server offline :(</p>
            </div>
        </div>
    </div>
</section>
<section class="sqmss-my-6">
    <div class="sqmss-container sqmss-mx-auto sm:sqmss-px-4">
        <!-- Server name -->
        <div class="sqmss-flex sqmss-flex-wrap sqmss-mb-5">
            <div class="sqmss-relative sqmss-flex-grow sqmss-max-w-full sqmss-flex-1 sqmss-px-4">
                <h1 class="sqmss-text-center sqmss-truncate data-server-name">{{ $server->last_query_result->name() }}</h1>
                <p class="sqmss-text-xl sqmss-font-light sqmss-text-center">
                    <span class="data-show-online {{ $server->online  ? '' : 'sqmss-hidden' }}"><span class="data-count">{{ $server->last_query_result->count() }}</span>(+<span class="data-queue">{{ $server->last_query_result->queue() }}</span>)/<span class="data-slots">{{ $server->last_query_result->slots() }}</span>(+<span class="data-reserved">{{ $server->last_query_result->reserved() }}</span>) {{ __('sqms-servers::pages/servers.server.players') }}</span>
                    <span class="sqmss-ext-red-600 sqmss-truncate data-show-offline {{ $server->online  ? 'sqmss-hidden' : '' }}">{{ __('sqms-servers::pages/servers.server.offline') }}</span>
                </p>
            </div>
        </div>

        <!-- Population -->
        <x-sqms-servers::player-list.population :server="$server" />
    </div>
</section>
@endsection

@pushOnce('styles')
@livewireStyles
@endPushOnce

@pushOnce('scripts')
@livewireScripts
<script src="https://cdn.jsdelivr.net/npm/morphdom@2.6.1/dist/morphdom.min.js"></script>
@endPushOnce
