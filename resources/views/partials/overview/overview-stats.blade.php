<?php
    /** @var App\Models\OverviewStat $overviewStat */
?>
<div class="divider">Quick Peek</div>
<div class="level">
    <div class="level-item has-text-centered">
        <div>
            <p class="heading">Total Matches</p>
            <p class="title">
                {{ number_format($overviewStat->total_matches) }}
            </p>
        </div>
    </div>
    <div class="level-item has-text-centered">
        <div>
            <p class="heading">Total Played (hours)</p>
            <p class="title">
                {{ number_format($overviewStat->time_played) }}
            </p>
        </div>
    </div>
    <div class="level-item has-text-centered">
        <div>
            <p class="heading">Total Players</p>
            <p class="title">{{ number_format($overviewStat->total_players) }}</p>
        </div>
    </div>
    <div class="level-item has-text-centered">
        <div>
            <p class="heading">Total Unique Players</p>
            <p class="title">
                {{ number_format($overviewStat->total_unique_players) }}
            </p>
        </div>
    </div>
</div>
