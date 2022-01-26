<?php
/** @var App\Models\Championship $championship */
/** @var App\Models\Matchup $matchup */
?>
<div class="columns">
    <div class="column">
        @include('partials.hcs.matchup_team_snippet', [
            'color' => 'is-success',
            'team' => $matchup->winner
        ])
    </div>
    <div class="column">
        @include('partials.hcs.matchup_team_snippet', [
            'color' => 'is-warning',
            'team' => $matchup->loser
        ])
    </div>
</div>
