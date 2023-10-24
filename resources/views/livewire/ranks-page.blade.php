<?php
/** @var \Illuminate\Support\Collection<\App\Models\Rank> $rankGroup */
?>
<div>
    @foreach ($ranks->chunk(5) as $rankGroup)
        <div class="columns mb-4">
            @foreach ($rankGroup as $rank)
               @include('partials.ranks.rank-item')
            @endforeach
        </div>
    @endforeach
</div>
