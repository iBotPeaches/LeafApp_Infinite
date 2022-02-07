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
        @if ($player->is_private)
            @include('partials.global.account_private')
        @else
            <article class="message">
                <div class="message-body">
                    Currently only showing medals from Ranked Matchmaking. Soon for all PVP.
                </div>
            </article>
            @foreach ($medals as $medal)
                <article class="tile is-flex">
                    <figure class="media-left">
                        <p class="image is-48x48">
                            <img class="{{ $medal->count === 0 ? 'is-greyscale' : '' }}" src="{{ $medal->image }}" />
                        </p>
                    </figure>
                    <div class="media-content">
                        <div class="content">
                            <p>
                                <strong style="white-space: nowrap">
                                    {{ $medal->name }}
                                </strong>
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
