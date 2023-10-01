<?php
/** @var App\Models\Analytic[]|null $topTen */
?>
<div wire:init="loadBadges">
    @if ($topTen != null && count($topTen) > 0)
        <article class="message is-small mb-2">
            <div class="message-header">
                <p>Top Ten</p>
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
