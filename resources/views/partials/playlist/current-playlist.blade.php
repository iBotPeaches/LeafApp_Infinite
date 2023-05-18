<?php
    /** @var App\Models\Playlist $playlist */
?>
<div class="card mb-2">
    <div class="card-image">
        <figure class="image">
            <img src="{{ $playlist->image }}" alt="{{ $playlist->name }}">
        </figure>
    </div>
    <div class="card-content">
        <h1 class="title is-4">
            {{ $playlist->name }}
        </h1>
        <h2 class="subtitle is-6">
            {{ $playlist->title }}
        </h2>
        <span class="tag">
            {{ $playlist->is_ranked ? 'Ranked' : 'Social' }}
        </span>
    </div>
</div>
