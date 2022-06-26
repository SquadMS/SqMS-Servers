<div>
    <div class="sqmss-flex sqmss-flex-wrap">
        <div class="sqmss-relative sqmss-flex-grow sqmss-max-w-full sqmss-flex-1 sqmss-px-4">
            <h2 class="sqmss-whitespace-nowrap sqmss-truncate sqmss-text-center sqmss-lh-0">
                <img src="{{ asset('themes/sqms-default-theme/static-images/factions/' . \SquadMS\Foundation\Helpers\FactionHelper::getFactionTag(\SquadMS\Foundation\Facades\SDKDataReader::getFactionForTeamID($server->last_query_result->layer(), $team->getId())) . '.svg') }}" style="height: 1em" />
                {{ $team->getName() }}
            </h2>
        </div>
    </div>

    @if (!count($team->getSquads()) && !count($team->getPlayers()))
        <div class="sqmss-flex sqmss-flex-wrap ">
            <div class="sqmss-relative sqmss-flex-grow sqmss-max-w-full sqmss-flex-1 sqmss-px-4">
                <p class="sqmss-text-xl sqmss-font-light sqmss-text-center">There are no players in this team</p>
            </div>
        </div>
    @else
        <!-- Squads -->
        @if (count($team->getSquads()))
            @foreach ($team->getSquads() as $squad)
                <x-sqms-servers::player-list.squad :id="$squad->getId()" :name="$squad->getName()" :players="$squad->getPlayers()" class="{{ !$loop->last ? 'sqmss-mb-3' : '' }}"/>
            @endforeach
        @endif

        <!-- Unassigned Players -->
        @if (count($team->getPlayers()))
            <x-sqms-servers::player-list.squad :players="$team->getPlayers()" class="sqmss-mt-3 sqmss-mb-3"/>
        @endif
    @endif
</div>
