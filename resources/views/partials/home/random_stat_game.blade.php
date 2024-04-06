<?php
/** @var App\Models\GamePlayer $gamePlayer */
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
            <a href="{{ route('game', [$gamePlayer->game]) }}">
                <strong>{{ $gamePlayer->game->name }}</strong>
            </a>
            <i>
                {{ $analyticClass->displayProperty($gamePlayer) }} {{ $analyticClass->unit() }} by
                @include('partials.links.player', ['player' => $gamePlayer->player])
                on
                {{ $gamePlayer->game->occurred_at->toFormattedDateString() }}
            </i>
        </div>
    </div>
</section>
