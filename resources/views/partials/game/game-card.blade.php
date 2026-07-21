<?php
/** @var App\Models\Game $game */
?>
<div class="card mb-3">
    <div class="card-image has-ribbon">
        @if ($game->season_number)
            <div class="ribbon is-small is-primary">
                <span
                    class="has-tooltip-success has-tooltip-arrow has-tooltip-bottom"
                    data-tooltip="{{ __('seasons.' . $game->season_number) }}"
                >
                    Season {{ $game->season_number }}
                </span>
            </div>
        @endif
        <figure class="image">
            <img src="{{ $game->map->image }}" alt="{{ $game->map->name }}">
        </figure>
    </div>
    <div class="card-content">
        <div class="media">
            <div class="media-content">
                <h1 class="title is-4">{{ $game->map->name }}</h1>
                <h2 class="subtitle is-6">
                    <span class="has-tooltip-arrow" data-tooltip="Base Mode: {{ $game->gamevariant?->category?->name ?? 'Unknown Gametype' }}">
                        {{ $game->gamevariant?->name ?? $game->category?->name }}
                    </span>
                    @if ($game->playlist)
                        - {{ $game->playlist->name }}
                        @if ($game->playlist->is_ranked)
                            @include('partials.game.playlist_type', ['playlist' => $game->playlist])
                        @endif
                    @else
                        - <i>Custom Game</i>
                    @endif
                    <br /><br />
                    @if ($game->duration_seconds)
                        <span class="tag is-dark">{{ $game->duration }}</span>
                    @endif
                </h2>
            </div>
        </div>
        <div class="content">
            <time class="is-size-7 local-date" datetime="{{ $game->occurred_at->toIso8601ZuluString() }}">
                {{ $game->occurred_at->toDayDateTimeString() }} (UTC)
            </time>
        </div>
    </div>
</div>
