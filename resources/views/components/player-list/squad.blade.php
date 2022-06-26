@props(['id' => 'TEAM', 'name' => 'Unassigned', 'players' => []])

<div {{ $attributes->merge(['class' => 'sqmss-px-4 squad', 'squad-id' => $id]) }}>
    <div class="sqmss-w-full">
        <div class="title sqmss-flex sqmss-items-center sqmss-bg-gray-100 sqmss-p-2">
            <h5 class="sqmss-truncate sqmss-mb-0">
                @if ($id !== 'TEAM')
                <span class="squad-id sqmss-inline-block p-1 sqmss-text-center sqmss-font-semibold sqmss-text-sm sqmss-align-baseline sqmss-leading-none sqmss-rounded sqmss-bg-gray-600 sqmss-me-2">#{{ $id }}</span>
                @endif
                {{ $name }}
            </h5>
        </div>
        <div class="players sqmss-flex sqmss-flex-col">
            @foreach ($players as $player)
                <x-sqms-servers::player-list.player :player="$player" />
            @endforeach
        </div>
    </div>
</div>
