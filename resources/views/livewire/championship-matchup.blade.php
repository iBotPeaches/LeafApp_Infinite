<?php
/** @var App\Models\Championship $championship */
/** @var App\Models\Matchup $matchup */
?>
<h1 class="title">{{ $championship->name }}</h1>
<h2 class="subtitle">
    {{ $matchup->bracket->description }} Round {{ $matchup->round }}
    (<a target="_blank" href="{{ $matchup->faceitUrl }}">FaceIt</a>)
</h2>
<div class="columns">
    <div class="column">
        @include('partials.hcs.matchup_team_snippet', [
            'color' => 'danger',
            'team' => $matchup->winner
        ])
    </div>
    <div class="column">
        @include('partials.hcs.matchup_team_snippet', [
            'color' => $matchup->loser->isBye() ? 'dark' : 'info',
            'team' => $matchup->loser
        ])
    </div>
</div>
