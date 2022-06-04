<?php
/** @var App\Models\serviceRecord $record */
/** @var App\Support\Analytics\AnalyticInterface $analytic */
?>
<section class="mt-3 card">
    <header class="card-header">
        <div class="card-header-title">
            {{ $analytic->title() }}
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
                <i>{{ $analytic->property($record) }}</i> {{ $analytic->unit() }}
            </div>
        </div>
    </div>
</section>
