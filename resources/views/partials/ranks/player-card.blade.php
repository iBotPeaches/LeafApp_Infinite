<?php
/** @var App\Models\Player $player */
?>
<div class="card mb-2">
    <div class="card-content">
        <h1 class="title is-4">
            {{ $player->gamertag }}
        </h1>
        <h2 class="subtitle is-6">
            Xp {{ number_format($player->xp) }}
        </h2>
        <div class="progress-wrapper is-clipped">
            <p class="progress-value has-text-white">
                {{ $player->percent_progress_to_hero }}% towards Hero Rank.
            </p>
            <progress
                class="progress"
                value="{{ $player->xp }}"
                max="9319350">
            </progress>
        </div>
    </div>
</div>
