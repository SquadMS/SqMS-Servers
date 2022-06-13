<div class="player flex min-width-0 p-2 border-start border-end border-b border-gray-100" player-id="{{ $player->getSteamId() }}">
    <a href="{{ route('profile', $player->getSteamId()) }}" class="truncate text-decoration-none">{{ $player->getName() }}</a>
</div>
