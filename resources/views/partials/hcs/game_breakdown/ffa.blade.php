<?php
/** @var App\Models\Game $game */
?>
@if ($games->isEmpty())
    <div class="notification is-warning">
        We are sorry. We could not automatically find the game(s) for this matchup.
    </div>
@endif
@foreach ($games as $game)
    <div class="tile is-ancestor">
        <div class="tile is-parent is-vertical">
            <article class="tile is-child notification is-dark">
                <p class="title">
                    <a href="{{ route('game', [$game]) }}">
                        {{ $game->map->name }}
                    </a>
                </p>
                <p class="subtitle">{{ $game->gamevariant?->name ?? $game->category?->name }}</p>
            </article>
        </div>
    </div>
@endforeach
