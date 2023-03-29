<?php
/** @var string $color */
/** @var App\Models\Matchup $matchup */
/** @var App\Models\MatchupTeam $team */

$team1 = $matchup->winner ?? $matchup->team1;
$team2 = $matchup->loser ?? $matchup->team2;
?>
@if ($championship->type->isFfa())
    <div class="columns">
        <div class="column">
            <h3 class="title is-3">Game(s)</h3>
            @include('partials.hcs.game_breakdown.ffa')
        </div>
        <div class="column">
            <h3 class="title is-3">Players</h3>
            @include('partials.hcs.team_snippet.ffa')
        </div>
    </div>
@else
    <div class="columns">
        <div class="column">
            @include('partials.hcs.team_snippet.4v4', [
                'color' => 'danger',
                'team' => $team1
            ])
        </div>
        <div class="column">
            @include('partials.hcs.team_snippet.4v4', [
                'color' => $team2?->isBye() ? 'dark' : 'info',
                'team' => $team2
            ])
        </div>
    </div>
@endif
