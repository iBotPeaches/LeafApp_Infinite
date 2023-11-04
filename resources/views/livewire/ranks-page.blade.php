<?php
/** @var \Illuminate\Support\Collection<\App\Models\Rank> $rankGroup */
?>
<div>
    @if ($player)
        @include('partials.ranks.player-card')
    @else
        <div class="notification is-light">
            Sign in with <a href="{{ route('login') }}">Google</a> and add your gamertag to see your progress.
        </div>
    @endif
    @foreach ($ranks->chunk(5) as $rankGroup)
        <div class="columns mb-4">
            @foreach ($rankGroup as $rank)
               @include('partials.ranks.rank-item')
            @endforeach
        </div>
    @endforeach
</div>
