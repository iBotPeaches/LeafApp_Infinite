<?php
    /** @var App\Models\OverviewStat|null $overviewStat */
?>
<div>
    @if ($overviewStat)
        @include('partials.overview.overview-stats')
    @else
        <div class="notification is-warning">
            No data available.
        </div>
    @endif
</div>
