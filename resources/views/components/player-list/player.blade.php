<div class="player d-flex min-width-0 p-2 border-start border-end border-bottom border-light" player-id="{{ $player->getSteamId() }}">
    <a href="{{ route('profile', $player->getSteamId()) }}" class="text-truncate text-decoration-none">{{ $player->getName() }}</a>
</div>
