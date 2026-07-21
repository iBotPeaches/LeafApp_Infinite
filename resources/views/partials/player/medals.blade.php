<div class="divider is-hidden-mobile">Medals</div>
<div class="grid mb-4">
    @foreach ($serviceRecord->hydrated_medals as $medal)
        <article class="cell media">
            <figure class="media-left">
                <p class="image is-48x48">
                    <img src="{{ $medal->image }}" alt="{{ $medal->name }}"/>
                </p>
            </figure>
            <div class="media-content">
                <div class="content">
                    <p>
                        <span
                            class="has-tooltip-arrow has-tooltip-arrow {{ $medal->tooltip_color }}"
                            data-tooltip="{{ $medal->description }}"
                        >
                            <a href="{{ route('medalLeaderboard', [$medal]) }}"
                               style="white-space: nowrap"
                               class="has-text-dark"
                            >
                                {{ $medal->name }}
                            </a>
                        </span>
                        <span class="is-clipped" style="display: block;">
                            {{ number_format($medal->count, 0) }}
                        </span>
                    </p>
                </div>
            </div>
        </article>
    @endforeach
</div>

