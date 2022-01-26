<?php
/** @var string $color */
/** @var App\Models\MatchupTeam $team */
?>
<article class="message {{ $color }}">
    <div class="message-body">
        {{ $team->name }}
    </div>
</article>
@foreach ($team->players as $player)
    <div class="card">
        <div class="card-content">
            <div class="media">
                <div class="media-left">
                    <figure class="image is-48x48">
                        <img src="{{ $player->emblem_url ?? '' }}">
                    </figure>
                </div>
                <div class="media-content">
                    <p class="title is-4">{{ $player->gamertag ?? '' }}</p>
                    <p class="subtitle is-6">1.0</p>
                </div>
            </div>
        </div>
    </div>
@endforeach
