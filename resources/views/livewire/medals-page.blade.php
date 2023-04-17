<?php
/** @var array $medals */
/** @var App\Models\Player $player */
?>
<div>
    @if (empty($medals))
        <div class="notification is-warning">
            No Medals pulled yet! Hold tight.
        </div>
    @else
        @if ($mode->is(\App\Enums\Mode::MATCHMADE_RANKED()) && !$isAllSeasons)
            <div class="notification is-warning">
                We currently cannot pull filtered medal data (ie ranked) from a specific season. If you have data it's from an older Leaf that could.
            </div>
        @endif
        @if ($player->is_private)
            @include('partials.global.account_private')
        @else
            @foreach ($medals as $medal)
                <article class="tile is-flex">
                    <figure class="media-left">
                        <p class="image is-48x48">
                            <img
                                class="{{ $medal->count === 0 ? 'is-greyscale' : '' }}"
                                src="{{ $medal->image }}"
                                alt="{{ $medal->name }}"
                            />
                        </p>
                    </figure>
                    <div class="media-content">
                        <div class="content">
                            <p>
                                <a
                                    href="{{ route('medalLeaderboard', [$medal]) }}"
                                    class="{{ $medal->text_color }}"
                                >
                                    <strong style="white-space: nowrap">
                                        {{ $medal->name }}
                                    </strong>
                                </a>
                                <br>
                                {{ $medal->description }}
                            </p>
                        </div>
                    </div>
                    <div class="media-right">
                    <span class="tag {{ $medal->count === 0 ? 'is-light' : 'is-dark' }}">
                        {{ number_format($medal->count) }}
                    </span>
                    </div>
                </article>
            @endforeach
        @endif
    @endif
</div>
