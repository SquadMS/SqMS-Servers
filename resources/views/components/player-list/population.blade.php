<div class="flex flex-wrap  data-player-list">
    @if ($server->last_query_result->population())
        @foreach ($server->last_query_result->population()->getTeams() as $team)
            <div class="w-full md:w-1/2 pr-4 pl-4">
                <x-sqms-servers::player-list.team :team="$team" :server="$server" />
            </div>
        @endforeach
    @elseif ($server->has_rcon_data)
        <div class="relative flex-grow max-w-full flex-1 px-4">
            <p class="text-xl font-light text-red-600">No population data available :(</p>
        </div>
    @endif
</div>
