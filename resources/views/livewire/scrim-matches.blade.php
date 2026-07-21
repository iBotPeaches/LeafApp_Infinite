<?php
    /** @var App\Models\Game $game */
?>
<div>
    @foreach ($games as $game)
        <div class="fixed-grid has-1-cols">
            <div class="grid">
                @if ($game->is_ffa)
                    @include('partials.scrim.matches_breakdown.ffa')
                @else
                    @include('partials.scrim.matches_breakdown.team')
                @endif
            </div>
        </div>
    @endforeach
</div>
