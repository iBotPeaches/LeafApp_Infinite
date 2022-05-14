<?php
/** @var App\Models\Csr[] $ranked */
?>
<div>
    @if ($player->is_private)
        @include('partials.global.account_private')
    @else
        @if ($latestMmr)
            @include('partials.player.mmr-card-row')
        @endif
        @if ($current)
            <article class="panel is-primary">
                <p class="panel-heading">
                    Competitive Play ({{ $isCurrentSeason || $isAllSeasons ? 'Current' : 'Previous' }})
                </p>
                <div class="panel-block is-block">
                    @if ($isAllSeasons)
                        <div class="notification is-dark">
                            Showing current season since "All Seasons" is useless in this view.
                        </div>
                    @endif
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
    @endif
</div>
