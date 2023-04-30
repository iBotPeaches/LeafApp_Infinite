<?php
/** @var App\Models\MedalAnalytic $medalAnalytic */
?>
<section class="mt-3 card">
    <header class="card-header">
        <div class="card-header-title">
            <a href="{{ route('medalLeaderboard', [$medalAnalytic->medal]) }}" class="{{ $medalAnalytic->medal->text_color }}">
                {{ $medalAnalytic->medal->name }}
            </a>
            <i class="is-size-7">
                &nbsp; - {{ $medalAnalytic->medal->description }}
            </i>
        </div>
    </header>
    <div class="card-content">
        <figure class="media-left">
            <p class="image is-64x64">
                <img src="{{ $medalAnalytic->medal->image }}" alt="{{ $medalAnalytic->medal->name }}"/>
            </p>
        </figure>
        <div class="media-content">
            <div class="content">
                <a href="{{ route('player', [$medalAnalytic->player]) }}">
                    <strong>{{ $medalAnalytic->player->gamertag }}</strong>
                </a>
                with {{ number_format($medalAnalytic->value) }} medals all time.
            </div>
        </div>
    </div>
</section>
