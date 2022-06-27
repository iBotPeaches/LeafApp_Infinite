<?php
/** @var App\Models\serviceRecord $record */
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
        <figure class="media-left">
            <p class="image is-64x64">
                <img src="{{ $record->player->emblem_url }}" alt="{{ $record->player->gamertag }}"/>
            </p>
        </figure>
        <div class="media-content">
            <div class="content">
                <a href="{{ route('player', [$record->player]) }}">
                    <strong>{{ $record->player->gamertag }}</strong>
                </a>
                <i>{{ $analyticClass->displayProperty($record) }}</i> {{ $analyticClass->unit() }}
            </div>
        </div>
    </div>
</section>
