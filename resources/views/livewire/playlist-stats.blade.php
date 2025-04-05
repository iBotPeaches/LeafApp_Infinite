<?php
    /** @var App\Models\PlaylistStat|null $stat */
?>
<div>
    @if ($stat)
        @include('partials.playlist.stats-stats')
    @endif
    @include('partials.playlist.disclaimer')
</div>
