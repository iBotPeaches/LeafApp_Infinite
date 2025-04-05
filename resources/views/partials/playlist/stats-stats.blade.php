<?php
/** @var App\Models\PlaylistStat|null $stat */
?>
<div class="divider">Quick Peek</div>
<div class="level">
    <div class="level-item has-text-centered">
        <div>
            <p class="heading">Total Matches</p>
            <p class="title">
                {{ number_format($stat->total_matches) }}
            </p>
        </div>
    </div>
    <div class="level-item has-text-centered">
        <div>
            <p class="heading">Total Players</p>
            <p class="title">{{ number_format($stat->total_players) }}</p>
        </div>
    </div>
    <div class="level-item has-text-centered">
        <div>
            <p class="heading">Total Unique Players</p>
            <p class="title">
                {{ number_format($stat->total_unique_players) }}
            </p>
        </div>
    </div>
</div>
