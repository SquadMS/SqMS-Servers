<div>
    <div class="row">
        <div class="col">
            <h2 class="text-nowrap text-truncate text-center lh-0">
                <img src="{{ asset('themes/sqms-default-theme/static-images/factions/' . \SquadMS\Foundation\Helpers\FactionHelper::getFactionTag(\SquadMS\Foundation\Facades\SDKDataReader::getFactionForTeamID($server->last_query_result->layer(), $team->getId())) . '.svg') }}" style="height: 1em" />
                {{ $team->getName() }}
            </h2>
        </div>
    </div>

    @if (!count($team->getSquads()) && !count($team->getPlayers()))
        <div class="row">
            <div class="col">
                <p class="lead text-center">There are no players in this team</p>
            </div>
        </div>
    @else
        <!-- Squads -->
        @if (count($team->getSquads()))
            @foreach ($team->getSquads() as $squad)
                <x-sqms-servers::player-list.squad :id="$squad->getId()" :name="$squad->getName()" :players="$squad->getPlayers()" class="{{ !$loop->last ? 'mb-3' : '' }}"/>
            @endforeach
        @endif

        <!-- Unassigned Players -->
        @if (count($team->getPlayers()))
            <x-sqms-servers::player-list.squad :players="$team->getPlayers()" class="mt-3 mb-3"/>
        @endif
    @endif
</div>
