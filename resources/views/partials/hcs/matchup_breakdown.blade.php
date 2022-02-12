<?php
/** @var string $color */
/** @var App\Models\MatchupTeam $team */
?>
@if ($championship->type->isFfa())
    @include('partials.hcs.team_snippet.ffa')
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
