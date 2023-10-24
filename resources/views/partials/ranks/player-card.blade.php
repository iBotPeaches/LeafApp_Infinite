<?php
/** @var App\Models\Player $player */
?>
<div class="card mb-2">
    <div class="card-image">
        <figure class="image">
            <img src="{{ $player->backdrop_url }}" alt="{{ $player->gamertag }}">
        </figure>
    </div>
    <div class="card-content">
        <h1 class="title is-4">
            {{ $player->gamertag }}
        </h1>
        <h2 class="subtitle is-6">
            {{ $player->xp }}
        </h2>
        <span class="tag">

        </span>
    </div>
</div>
