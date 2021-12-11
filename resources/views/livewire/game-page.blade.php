<?php
/** @var App\Models\Game $game */
?>
<div class="columns">
    <div class="column">
        @include('partials.game.game-card')
        @include('partials.game.team-breakdown')
        @if ($game->players->count() <= 1)
            <livewire:update-game-panel :game="$game"></livewire:update-game-panel>
        @endif
    </div>
    <div class="column is-four-fifths">
        @foreach ($groupedGamePlayers as $gamePlayers)
            @include('partials.game.team-table')
        @endforeach
    </div>
</div>
