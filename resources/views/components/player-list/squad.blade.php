@props(['id' => 'TEAM', 'name' => 'Unassigned', 'players' => []])

<div {{ $attributes->merge(['class' => 'row squad', 'squad-id' => $id]) }}>
    <div class="col-12">
        <div class="title d-flex align-items-center bg-light p-2">
            <h5 class="text-truncate mb-0">
                @if ($id !== 'TEAM')
                <span class="squad-id badge bg-secondary me-2">#{{ $id }}</span>
                @endif
                {{ $name }}
            </h5>
        </div>
        <div class="players d-flex flex-column">
            @foreach ($players as $player)
                <x-sqms-servers::player-list.player :player="$player" />
            @endforeach
        </div>
    </div>
</div>
