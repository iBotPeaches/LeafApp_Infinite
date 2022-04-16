<?php
/** @var App\Models\Csr[] $ranked */
?>
<div>
    @if ($latestMmr)
        @include('partials.player.mmr-card-row')
    @endif
    @if ($current)
        <article class="panel is-primary">
            <p class="panel-heading">
                Competitive Play (Current)
            </p>
            <div class="panel-block is-block">
                <div class="columns is-centered">
                    @foreach ($current as $playlist)
                        @include('partials.player.csr-card-row')
                    @endforeach
                </div>
            </div>
        </article>
    @endif
    @if ($season->isNotEmpty() && $allTime->isNotEmpty())
        <div class="divider">Records</div>
        <div class="columns">
            <div class="column">
                <h5 class="title is-5">
                    Season High
                    <span class="subtitle is-6 is-pulled-right">
                    CSR
                </span>
                </h5>
                @foreach ($season as $playlist)
                    @if ($playlist->hasPlacementsDone())
                        @include('partials.player.csr-minimal-card-row')
                    @endif
                @endforeach
            </div>
            <div class="column">
                <h5 class="title is-5">
                    All Time
                    <span class="subtitle is-6 is-pulled-right">
                    CSR
                </span>
                </h5>
                @foreach ($allTime as $playlist)
                    @if ($playlist->hasPlacementsDone())
                        @include('partials.player.csr-minimal-card-row')
                    @endif
                @endforeach
            </div>
        </div>
    @endif
</div>
