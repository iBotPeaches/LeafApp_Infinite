<?php
/** @var App\Models\Game $game */
?>
<div class="card mb-3">
    <div class="card-image">
        <figure class="image is-4by3">
            <img src="{{ $game->map->thumbnail_url }}" alt="{{ $game->map->name }}">
        </figure>
    </div>
    <div class="card-content">
        <div class="media">
            <div class="media-content">
                <p class="title is-4">{{ $game->map->name }}</p>
                <p class="subtitle is-6">
                    {{ $game->category->name }}
                    @if ($game->queue)
                        ({{ $game->title }}{!! $game->icon !!})
                    @endif
                </p>
            </div>
        </div>
        <div class="content">
            @if ($game->playlist)
                {{ $game->playlist->name }}
                @if ($game->playlist->is_ranked)
                    @include('partials.game.playlist_type', ['playlist' => $game->playlist])
                @endif
            @else
                <i>Custom Game</i>
            @endif
            <br />
            <time datetime="{{ $game->occurred_at->toIso8601ZuluString() }}">
                {{ $game->occurred_at->diffForHumans() }}
            </time>
        </div>
    </div>
</div>
