<?php
/** @var App\Models\Rank $rank */
?>
<article class="column">
    <div class="notification is-flex is-light {{ $rank->threshold > $player?->xp ? 'is-danger' : 'is-success' }}">
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
                        {{ number_format($rank->threshold) }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</article>
