<?php
    /** @var App\Models\MedalAnalytic[]|null $medals */
    /** @var App\Models\Analytic[]|null $topTen */
?>
<div wire:init="loadBadges">
    @if ($medals != null)
        <article class="message is-small">
            <div class="message-header">
                <p>Medal Leaderboard</p>
            </div>
            <div class="message-body">
                @foreach ($medals as $medal)
                    <article class="tile">
                        <figure class="media-left">
                            <p class="image is-24x24">
                                <img src="{{ $medal['medal']->image }}" alt="{{ $medal['medal']->name }}"/>
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                @th($medal->place) in <a href="{{ route('medalLeaderboard', [$medal->medal]) }}">{{ $medal->medal->name }}</a> medals with {{ number_format($medal->value) }}
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </article>
    @endif
    @if ($topTen != null)
        <article class="message is-small">
            <div class="message-header">
                <p>Analaytic Leaderboard</p>
            </div>
            <div class="message-body">
                @foreach ($topTen as $analytic)
                    @th($analytic->place) in <a href="{{ route('topTenLeaderboard', [$analytic->enum->key()]) }}">{{ $analytic->enum->title() }}</a> with {{ $analytic->value }}{{ $analytic->enum->unit() }}
                    <br />
                @endforeach
            </div>
        </article>
    @endif
</div>
