<?php
/** @var App\Models\Game $game */
?>
<div class="columns">
    <div class="column">
        @include('partials.game.game-card')
        @include('partials.game.team-breakdown')
        @if ($game->outdated)
            <livewire:update-game-panel :game="$game" />
        @endif
        <hr />
        @include('partials.game.player-rank-changes')
    </div>
    <div class="column is-four-fifths">
        @foreach ($groupedGamePlayers as $gamePlayers)
            @include('partials.game.team-table')
        @endforeach
        @if (! $game->outdated)
            <div class="divider">Powerful Medals (Legendary or Heroic)</div>
            @include('partials.game.powerful_medals')
        @endif
    </div>
</div>
