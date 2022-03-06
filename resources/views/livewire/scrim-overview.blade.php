<div class="columns">
    <div class="column">
        @include('partials.scrim.team_breakdown', [
            'color' => 'danger',
            'team' => $team1
        ])
    </div>
    <div class="column">
        @include('partials.scrim.team_breakdown', [
            'color' => 'info',
            'team' => $team2
        ])
    </div>
</div>
