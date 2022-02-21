<?php
/** @var App\Models\Csr[] $ranked */
?>
@foreach ($ranked as $key => $group)
<article class="panel is-primary">
    <p class="panel-heading">
        Competitive Play ({{ App\Enums\CompetitiveMode::coerce($key)->description }})
    </p>
    <div class="panel-block is-block">
        <div class="columns is-centered">
            @foreach ($group as $playlist)
                @include('partials.player.csr-card-row')
            @endforeach
        </div>
    </div>
</article>
@endforeach
@if ($allTime)
    <article class="panel is-info">
        <p class="panel-heading">
            Competitive Play (All Time)
        </p>
        <div class="panel-block is-block">
            <div class="columns is-centered">
                @foreach ($allTime as $playlist)
                    @include('partials.player.csr-card-row')
                @endforeach
            </div>
        </div>
    </article>
@endif
