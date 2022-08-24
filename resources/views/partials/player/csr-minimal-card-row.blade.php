<article class="tile is-flex">
    <figure class="media-left">
        <p class="image is-48x48">
            <img src="{{ $playlist->toCsrObject()->url() }}" alt="{{ $playlist->rank }}"/>
        </p>
    </figure>
    <div class="media-content">
        <div class="content">
            <p>
                <strong style="white-space: nowrap">
                    {{ $playlist?->playlist?->name ?? $playlist->title }}
                    {!! $playlist->icon !!}
                </strong>
            </p>
        </div>
    </div>
    <div class="media-right">
        <span class="tag is-dark">
            {{ number_format($playlist->csr) }}
        </span>
    </div>
</article>
