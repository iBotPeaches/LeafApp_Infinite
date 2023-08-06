<?php
    /** @var App\Models\MedalAnalytic[]|null $medals */
    /** @var App\Models\Analytic[]|null $topTen */
?>
<div wire:init="loadBadges">
    @if ($medals != null && count($medals) > 0)
        <article class="message is-small mb-2">
            <div class="message-header">
                <p>Medal Leaderboard</p>
            </div>
            <div class="message-body">
                @foreach ($medals as $medal)
                    <article class="tile is-flex">
                        <figure class="media-left">
                            <p class="image is-24x24">
                                <img src="{{ $medal['medal']->image }}" alt="{{ $medal['medal']->name }}"/>
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content is-clipped">
                                @th($medal->place) in <a href="{{ route('medalLeaderboard', [$medal->medal]) }}">{{ $medal->medal->name }}</a> medals with {{ number_format($medal->value) }}
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </article>
    @endif
    @if ($topTen != null && count($topTen) > 0)
        <article class="message is-small mb-2">
            <div class="message-header">
                <p>Analytic Leaderboard</p>
            </div>
            <div class="message-body">
                @foreach ($topTen as $analytic)
                    @th($analytic->place) in <a href="{{ route('topTenLeaderboard', [$analytic->enum->key()]) }}">{{ $analytic->enum->title() }}</a> with {{ $analytic->enum->displayProperty($analytic) }}&nbsp;{{ $analytic->enum->unit() }}
                    <br />
                @endforeach
            </div>
        </article>
    @endif
</div>
