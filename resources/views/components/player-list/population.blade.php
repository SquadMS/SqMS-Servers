<div class="sqmss-flex sqmss-flex-wrap data-player-list">
    @if ($server->last_query_result->population())
        @foreach ($server->last_query_result->population()->getTeams() as $team)
            <div class="sqmss-w-full md:sqmss-w-1/2 sqmss-pr-4 sqmss-pl-4">
                <x-sqms-servers::player-list.team :team="$team" :server="$server" />
            </div>
        @endforeach
    @elseif ($server->has_rcon_data)
        <div class="rsqmss-elative sqmss-flex-grow sqmss-max-w-full sqmss-flex-1 sqmss-px-4">
            <p class="sqmss-text-xl sqmss-font-light sqmss-text-red-600">No population data available :(</p>
        </div>
    @endif
</div>
