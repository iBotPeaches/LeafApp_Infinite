<?php
/** @var App\Models\Game $game */
/** @var App\Models\GamePlayer $winningPlayer */
$winningPlayer = $game->players->sortBy('rank')->first();
?>
<article class="tile is-child notification is-dark">
    <p class="title">
        <a href="{{ route('game', [$game]) }}">
            {{ $game->map->name }}
        </a>
        @if ($winningPlayer)
            <span class="is-pulled-right">
                <span class="tag is-light">{{ $winningPlayer->kills }}</span>
            </span>
        @endif
    </p>
    <p class="subtitle">
        {{ $game->gamevariant?->name ?? $game->category?->name }}
        @if ($winningPlayer)
            <span class="is-pulled-right">
                <span class="has-tooltip-arrow" data-tooltip="{{ $winningPlayer->player->gamertag }}">
                    <img class="image is-inline is-32x32" src="{{ $winningPlayer->player->emblem_url }}" alt="Emblem" />
                </span>
            </span>
        @endif
    </p>
</article>
