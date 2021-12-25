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
                    <abbr title="{{ $medal->description }}">
                        <strong style="white-space: nowrap">
                            {{ $medal->name }}
                        </strong>
                    </abbr>
                    <br>
                    {{ $medal->count }}
                </p>
            </div>
        </div>
    </article>
@endforeach
