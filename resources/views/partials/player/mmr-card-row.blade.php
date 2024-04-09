<?php
    /** @var App\Models\Game $latestMmr */
?>
<article class="message is-dark">
    <div class="message-header">
        <p><abbr title="Team MatchMaking Ratio">MMR</abbr></p>
        <span class="has-tooltip-arrow" data-tooltip="Obtained via FFA games (As individual MMR is returned)">
            <i class="fas fa-question"></i>
        </span>
    </div>
    <div class="message-body">
        <strong>{{ number_format($latestMmr->personal->mmr) }}</strong>
        in a <a href="{{ route('game', [$latestMmr]) }}">{{ $latestMmr->name }}</a>.

        <span class="is-pulled-right">
            <span class="tag is-dark">{{ number_format($latestMmr->occurred_at->diffInDays(absolute: true)) }} days old</span>
        </span>
    </div>
</article>
