<?php
/** @var App\Models\Championship $championship */
/** @var App\Models\Matchup[]|Illuminate\Support\Collection $matchups */
?>
<div class="table-container">
    @if ($championship->type->isFfa())
        @include('partials.hcs.bracket_table.ffa')
    @else
        @include('partials.hcs.bracket_table.4v4')
    @endif
</div>
