<?php
/** @var App\Models\Game $game */
?>
<div class="columns">
    <div class="column">
        @include('partials.game.game-card')
        @include('partials.game.team-breakdown')
        @if ($game->outdated && !config('services.autocode.disabled'))
            <livewire:update-game-panel :game="$game" />
        @endif
        <hr />
        @include('partials.game.export_card')
        @include('partials.game.player-rank-changes')
    </div>
    <div class="column is-four-fifths">
        @foreach ($groupedGamePlayers as $teamId => $gamePlayers)
            @include('partials.game.team-table', ['team' => $game->findTeamFromTeamId($teamId)])
        @endforeach
        @if (! $game->outdated)
            <div class="divider">Medals</div>
            @include('partials.game.powerful_medals')
        @endif
    </div>
</div>
