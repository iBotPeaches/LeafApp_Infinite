<?php
/** @var string $color */
/** @var App\Models\MatchupTeam $team */
?>
@foreach ($matchup->matchupTeams->sortBy('points') as $matchupTeam)
    <div class="card has-background-{{ $matchupTeam->isWinner() ? 'success' : 'dark' }}-light">
        <div class="card-content">
            <div class="media">
                <div class="media-left">
                    <figure class="image is-48x48">
                        <img src="{{ $matchupTeam->getPlayer()?->emblem_url ?? '' }}">
                    </figure>
                </div>
                <div class="media-content">
                    <p class="title is-4">
                        @if ($matchupTeam->getPlayer())
                            <a href="{{ route('player', [$matchupTeam->getPlayer()]) }}">
                                {{ $matchupTeam->getPlayer()?->gamertag ?? '' }}
                            </a>
                        @else
                            <span>{{ $matchupTeam->name }}</span>
                        @endif
                    </p>
                    <p class="subtitle is-6"><i>@th($matchupTeam->points) place</i></p>
                </div>
            </div>
        </div>
    </div>
@endforeach
