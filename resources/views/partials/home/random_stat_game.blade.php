<?php
/** @var App\Models\GamePlayer $gamePlayer */
/** @var App\Support\Analytics\AnalyticInterface $analytic */
?>
<section class="mt-3 card">
    <header class="card-header">
        <div class="card-header-title">
            {{ $analytic->title() }}
        </div>
    </header>
    <div class="card-content">
        <div class="content">
            <a href="{{ route('game', [$gamePlayer->game]) }}">
                <strong>{{ $gamePlayer->game->name }}</strong>
            </a>
            <i>
                {{ $analytic->property($gamePlayer) }} {{ $analytic->unit() }} by
                <a href="{{ route('player', [$gamePlayer->player]) }}">
                    {{ $gamePlayer->player->gamertag }}
                </a>
                on
                {{ $gamePlayer->game->occurred_at->toFormattedDateString() }}
            </i>
        </div>
    </div>
</section>
