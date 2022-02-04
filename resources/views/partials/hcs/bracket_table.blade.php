<?php
/** @var App\Models\Matchup[]|Illuminate\Support\Collection $matchups */
?>
<div class="table-container">
    @if ($championship->is_ffa)
        @include('partials.hcs.bracket_table.ffa')
    @else
        @include('partials.hcs.bracket_table.4v4')
    @endif
</div>
