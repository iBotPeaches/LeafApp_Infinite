<?php
/** @var string $color */
/** @var App\Models\MatchupTeam $team */
/** @var App\Models\Matchup $matchup */
?>
<article class="message is-{{ $color }}">
    <div class="message-body">
        <strong>{{ $team->name }}</strong>
        <span class="is-pulled-right">
            <span class="tag is-{{ $color }}">
                 @if ($matchup->started_at && $matchup->ended_at)
                    {{ $team->points }}
                 @else
                    -
                 @endif
            </span>
        </span>
    </div>
</article>
@if ($team->isBye())
    <div class="message-body">
        This match was a bye. No games played.
    </div>
@endif
@foreach ($team->players as $player)
    <div class="card has-background-{{ $color }}-light">
        <div class="card-content">
            <div class="media">
                <div class="media-left">
                    <figure class="image is-48x48">
                        <img src="{{ $player->emblem_url ?? '' }}" alt="Emblem">
                    </figure>
                </div>
                <div class="media-content">
                    <p class="title is-4">
                        @include('partials.links.player', ['player' => $player])
                    </p>
                    <p class="subtitle is-6"><i>-</i></p>
                </div>
            </div>
        </div>
    </div>
@endforeach
