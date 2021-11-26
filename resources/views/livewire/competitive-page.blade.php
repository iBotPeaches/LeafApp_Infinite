<?php
/** @var App\Models\Csr[] $ranked */
?>
<article class="panel is-primary">
    <p class="panel-heading">
        Competitive Play
    </p>
    <div class="panel-block is-block">
        <div class="columns is-centered">
            @foreach ($ranked as $playlist)
                <div class="column" style="min-height: 100%;">
                    <div class="card">
                        <div class="card-header">
                            <span class="card-header-title">
                                {{ $playlist->title }}
                                &nbsp;{!! $playlist->icon !!}
                            </span>
                        </div>
                        <div class="card-image has-background-light">
                            <figure class="image is-4by3">
                                <img src="{{ $playlist->tier_image_url }}" alt="{{ $playlist->rank }}">
                            </figure>
                        </div>
                        <div class="card-content">
                            <p class="title is-3">{{ $playlist->rank }}</p>
                            <p class="subtitle is-5">
                                @if ($playlist->matches_remaining > 0)
                                    <i>In Placements</i>
                                @else
                                    CSR: {{ number_format($playlist->csr) }}
                                @endif
                            </p>

                            <div class="content">
                                <progress
                                    class="progress {{ $playlist->getRankPercentColor() }}"
                                    value="{{ $playlist->csr }}"
                                    max="{{ $playlist->next_csr }}"
                                >%{{ number_format($playlist->next_rank_percent, 2) }}</progress>
                                <br>
                                @if ($playlist->hasNextRank())
                                    <span>Up Next: {{ $playlist->next_rank }}</span>
                                @else
                                    <span>
                                        {{ $playlist->matches_remaining }}
                                        {{ Str::plural('match', $playlist->matches_remaining) }} remaining.
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</article>
