<div class="column is-flex is-flex-direction-column is-align-items-stretch">
    <div class="card" style="height: 100%;">
        <div class="card-header">
            <span class="card-header-title">
                {{ $playlist?->playlist?->name ?? $playlist->title }}
                {!! $playlist->icon !!}
            </span>
        </div>
        <div class="card-image has-background-light">
            <figure class="image">
                <img src="{{ $playlist->toCsrObject()->url() }}" alt="{{ $playlist->rank }}">
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
                @if (! $playlist->isOnyx())
                    <progress
                        class="progress {{ $playlist->getRankPercentColor() }}"
                        value="{{ $playlist->current_xp_for_level }}"
                        max="{{ $playlist->next_xp_for_level }}"
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
                @endif
            </div>
        </div>
    </div>
</div>
