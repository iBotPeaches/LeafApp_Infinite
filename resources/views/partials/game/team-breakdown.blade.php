<?php
/** @var App\Models\Game $game */
?>
@foreach ($game->teams as $team)
    <article class="message mb-3 {{ $team->color }}">
        <div class="message-body">
            <article class="media">
                <div class="media-left">
                    <figure class="image is-32x32">
                        <img src="{{ $team->emblem_url }}" alt="{{ $team->name }}">
                    </figure>
                </div>
                <div class="media-content">
                    <div class="content">
                        <strong>{{ $team->name }}</strong>
                        @if ($team->winning_percent)
                            <span class="has-tooltip-arrow {{ $team->tooltip_color }}" data-tooltip="Winning Chance">
                                <i>({{ number_format($team->winning_percent, 1) }}%)</i>
                            </span>
                        @endif
                        @if (! $game->outdated)
                            <span class="is-pulled-right tag {{ $team->color }}">{{ $team->final_score ?? '?' }}</span>
                        @endif
                    </div>
                </div>
            </article>
        </div>
    </article>
@endforeach
