<?php
/** @var App\Models\Game $game */
?>
@foreach ($games as $game)
    <div class="tile is-ancestor">
        <div class="tile is-parent is-vertical">
            <article class="tile is-child notification is-dark">
                <p class="title">
                    <a href="{{ route('game', [$game]) }}">
                        {{ $game->map->name }}
                    </a>
                </p>
                <p class="subtitle">{{ $game->category->name }}</p>
            </article>
        </div>
    </div>
@endforeach
