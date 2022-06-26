<div class="player sqmss-flex sqmss-min-width-0 sqmss-p-2 sqmss-border-start sqmss-border-end sqmss-border-b sqmss-border-gray-100" player-id="{{ $player->getSteamId() }}">
    <a href="{{ route('profile', $player->getSteamId()) }}" class="sqmss-truncate sqmss-text-decoration-none">{{ $player->getName() }}</a>
</div>
