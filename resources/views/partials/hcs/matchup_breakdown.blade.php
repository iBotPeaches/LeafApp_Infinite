<?php
/** @var string $color */
/** @var App\Models\MatchupTeam $team */
?>
<<<<<<< HEAD
@if ($championship->type->isFfa())
    <div class="columns">
        <div class="column">
            <h3 class="title is-3">Games</h3>
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
                'team' => $matchup->winner
            ])
        </div>
        <div class="column">
            @include('partials.hcs.team_snippet.4v4', [
                'color' => $matchup->loser->isBye() ? 'dark' : 'info',
                'team' => $matchup->loser
            ])
        </div>
    </div>
@endif
