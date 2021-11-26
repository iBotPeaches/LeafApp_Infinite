<?php
/** @var App\Models\ServiceRecord $serviceRecord */
?>
<div>
    <div class="level">
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">KD</p>
                <p class="title">{{ $serviceRecord->kd }}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">KDA</p>
                <p class="title">{{ $serviceRecord->kda }}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Total Matches</p>
                <p class="title">{{ $serviceRecord->total_matches }}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Win Percent</p>
                <p class="title">{{ number_format($serviceRecord->win_percent, 2) }}%</p>
            </div>
        </div>
    </div>
    <hr />
    <div class="level">
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Kills</p>
                <p class="title">{{ $serviceRecord->kills }}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Deaths</p>
                <p class="title">{{ $serviceRecord->deaths }}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Medal Count</p>
                <p class="title">{{ $serviceRecord->medal_count }}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Accuracy</p>
                <p class="title">{{ number_format($serviceRecord->accuracy, 2) }}%</p>
            </div>
        </div>
    </div>
    <hr />
    <div class="level">
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Melee</p>
                <p class="title">{{ $serviceRecord->kills_melee }}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Grenades</p>
                <p class="title">{{ $serviceRecord->kills_grenade }}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Headshots</p>
                <p class="title">{{ $serviceRecord->kills_headshot }}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Power</p>
                <p class="title">{{ $serviceRecord->kills_power }}</p>
            </div>
        </div>
    </div>
    <hr />
    <div class="level">
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Time Played (hours)</p>
                <p class="title">{{ $serviceRecord->time_played }}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Avg Score</p>
                <p class="title">{{ number_format($serviceRecord->average_score, 2) }}</p>
            </div>
        </div>
    </div>
</div>
