<?php
    /** @var App\Models\PlaylistStat|null $stat */
?>
<div>
    @include('partials.playlist.inactive-disclaimer')
    @if ($stat)
        @include('partials.playlist.stats-stats')
    @endif
    @include('partials.playlist.stats-analytics')
    @include('partials.playlist.disclaimer')
</div>
