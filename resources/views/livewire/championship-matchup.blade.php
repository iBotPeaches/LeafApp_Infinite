<?php
/** @var App\Models\Championship $championship */
/** @var App\Models\Matchup $matchup */
/** @var App\Models\Game[] $games */
/** @var array $aggregateStats */
?>
<div>
    <h1 class="title">{{ $championship->name }}</h1>
    <h2 class="subtitle">
        {{ $matchup->bracket?->description }} Round {{ $matchup->round }}
        (<a target="_blank" href="{{ $matchup->faceitUrl }}" rel="nofollow">FaceIt</a>)
    </h2>
    @include('partials.hcs.matchup_breakdown')
</div>
