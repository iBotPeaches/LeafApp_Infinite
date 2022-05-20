<?php
/** @var App\Models\Medal $medal */
?>
<section class="mt-3 card">
    <header class="card-header">
        <div class="card-header-title">
            Random Medal
        </div>
    </header>
    <div class="card-content">
        <figure class="media-left">
            <p class="image is-64x64">
                <img src="{{ $medal->image }}" alt="{{ $medal->name }}"/>
            </p>
        </figure>
        <div class="media-content">
            <div class="content">
                <a href="{{ route('medalLeaderboard', [$medal]) }}" class="{{ $medal->text_color }}">
                    <strong>{{ $medal->name }}</strong>
                </a>
                <i>{{ $medal->description }}</i>
            </div>
        </div>
    </div>
</section>
