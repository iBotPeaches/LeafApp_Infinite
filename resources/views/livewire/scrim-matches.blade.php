<?php
    /** @var App\Models\Game $game */
?>
<div>
    @foreach ($games as $game)
        <div class="tile is-ancestor">
            <div class="tile is-parent is-vertical">
                <article class="tile is-child notification {{ $game->winner->color }}">
                    <p class="title">
                        <a href="{{ route('game', [$game]) }}">
                            {{ $game->map->name }}
                        </a>
                        <span class="is-pulled-right">
                            <span class="tag is-dark">{{ $game->score }}</span>
                        </span>
                    </p>
                    <p class="subtitle">
                        {{ $game->category->name }}
                        <span class="is-pulled-right">
                            @foreach ($game->winner->players as $player)
                                <span class="has-tooltip-arrow" data-tooltip="{{ $player->player->gamertag }}">
                                    <img class="image is-inline is-32x32" src="{{ $player->player->emblem_url }}" />
                                </span>
                            @endforeach
                        </span>
                    </p>
                </article>
            </div>
        </div>
    @endforeach
</div>
