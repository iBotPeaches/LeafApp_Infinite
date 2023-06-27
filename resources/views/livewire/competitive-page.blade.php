<?php
/** @var App\Models\Csr[] $current */
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
                    <div class="table-container">
                        <table class="table is-striped is-hoverable is-fullwidth">
                            <thead>
                            <tr>
                                <th>Playlist</th>
                                <th>Rank</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($current as $playlist)
                                <tr>
                                    <td>
                                        {{ $playlist?->playlist?->name ?? $playlist->title }}
                                        {!! $playlist->icon !!}
                                    </td>
                                    <td>
                                        <article class="media">
                                            <div class="card-image {{ 'is-' . Str::slug($playlist->rank) }}">
                                                <p class="image is-32x32">
                                                    <img src="{{ $playlist->toCsrObject()->url() }}" alt="{{ $playlist->rank }}">
                                                </p>
                                            </div>
                                            <div class="media-content">
                                                <div class="content" style="white-space: nowrap">
                                                    &nbsp;{{ $playlist->rank }}
                                                </div>
                                            </div>
                                        </article>
                                    </td>
                                    <td>
                                        @if ($playlist->matches_remaining > 0)
                                            <i>In Placements</i>
                                        @else
                                            CSR: {{ number_format($playlist->csr) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if (! $playlist->isOnyx())
                                            <div
                                                class="has-tooltip-arrow has-tooltip-text-centered"
                                                data-tooltip="{{ $playlist->getRankPercentTooltip() }}"
                                            >
                                                <div class="progress-wrapper is-clipped">
                                                    @if ($playlist->matches_remaining > 0)
                                                        <p class="progress-value has-text-white">{{ $playlist->matches_remaining }} matches left.</p>
                                                    @else
                                                        <p class="progress-value has-text-white">{{ number_format($playlist->csr) }} / {{ number_format($playlist->next_csr) }} CSR</p>
                                                    @endif
                                                    <progress
                                                        class="progress {{ $playlist->getRankPercentColor() }}"
                                                        value="{{ $playlist->current_xp_for_level }}"
                                                        max="{{ $playlist->next_xp_for_level }}">
                                                    </progress>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>
        @endif
        @if ($allTime->isNotEmpty())
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
