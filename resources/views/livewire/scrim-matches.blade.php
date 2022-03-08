<?php
    /** @var App\Models\Game $game */
?>
<div>
    @foreach ($games as $game)
        <div class="tile is-ancestor">
            <div class="tile is-parent is-vertical">
                @if ($game->is_ffa)
                    @include('partials.scrim.matches_breakdown.ffa')
                @else
                    @include('partials.scrim.matches_breakdown.team')
                @endif
            </div>
        </div>
    @endforeach
</div>
