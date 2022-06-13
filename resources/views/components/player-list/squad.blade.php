@props(['id' => 'TEAM', 'name' => 'Unassigned', 'players' => []])

<div {{ $attributes->merge(['class' => 'row squad', 'squad-id' => $id]) }}>
    <div class="w-full">
        <div class="title flex items-center bg-gray-100 p-2">
            <h5 class="truncate mb-0">
                @if ($id !== 'TEAM')
                <span class="squad-id inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded bg-gray-600 me-2">#{{ $id }}</span>
                @endif
                {{ $name }}
            </h5>
        </div>
        <div class="players flex flex-col">
            @foreach ($players as $player)
                <x-sqms-servers::player-list.player :player="$player" />
            @endforeach
        </div>
    </div>
</div>
