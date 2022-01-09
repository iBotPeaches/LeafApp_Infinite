@foreach ($medalGroup as $medal)
    <article class="tile">
        <figure class="media-left">
            <p class="image is-48x48">
                <img src="{{ $medal->thumbnail_url }}" />
            </p>
        </figure>
        <div class="media-content">
            <div class="content">
                <p>
                    <span class="has-tooltip-arrow" data-tooltip="{{ $medal->description }}">
                        <strong style="white-space: nowrap">
                            {{ $medal->name }}
                        </strong>
                    </span>
                    <br>
                    {{ $medal->count }}
                </p>
            </div>
        </div>
    </article>
@endforeach
