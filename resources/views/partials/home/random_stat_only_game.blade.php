<?php
/** @var App\Models\Analytic $game */
/** @var App\Support\Analytics\AnalyticInterface $analyticClass */
?>
<section class="mt-3 card">
    <header class="card-header">
        <div class="card-header-title">
            <a href="{{ route('topTenLeaderboard', ['key' => $analytic->key]) }}">
                {{ $analyticClass->title() }}
            </a>
        </div>
    </header>
    <div class="card-content">
        <div class="content">
            <a href="{{ route('game', [$analytic->game]) }}">
                <strong>{{ $analytic->game->name }}</strong>
            </a>
            <i>
                {{ $analyticClass->displayProperty($game) }} {{ $analyticClass->unit() }}
                on
                {{ $analytic->game->occurred_at->toFormattedDateString() }}
            </i>
        </div>
    </div>
</section>
