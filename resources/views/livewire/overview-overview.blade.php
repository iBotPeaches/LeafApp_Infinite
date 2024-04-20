<?php
    /** @var App\Models\OverviewStat|null $overviewStat */
?>
<div>
    @if ($overviewStat)
        @include('partials.overview.overview-stats')
    @else
        <div class="notification is-warning">
            Uh oh - whatever combination of filters you've applied has resulted in no data being found.
        </div>
    @endif
</div>
