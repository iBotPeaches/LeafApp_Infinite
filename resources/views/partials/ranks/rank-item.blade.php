<?php
/** @var App\Models\Rank $rank */
?>
<article class="column">
    <div class="notification is-flex is-light is-info">
        <figure class="media-left">
            <p class="image is-32x32">
                <img src="{{ $rank->icon }}" alt="{{ $rank->name }}"/>
            </p>
        </figure>
        <div class="media-content">
            <div class="content">
                <p>
                    <span>
                        {{ $rank->name }}
                    </span>
                    <span class="is-clipped" style="display: block;">
                        {{ number_format($rank->threshold, 0) }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</article>
