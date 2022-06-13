<div class="row data-player-list">
    @if ($server->last_query_result->population())
        @foreach ($server->last_query_result->population()->getTeams() as $team)
            <div class="col-12 col-md-6">
                <x-sqms-default-theme::player-list.team :team="$team" :server="$server" />
            </div>
        @endforeach
    @elseif ($server->has_rcon_data)
        <div class="col">
            <p class="lead text-danger">No population data available :(</p>
        </div>
    @endif
</div>
