<?php
/** @var App\Models\Medal $medal */
?>
<section class="mt-3 card">
    <header class="card-header">
        <p class="card-header-title">
            Random Medal
        </p>
    </header>
    <div class="card-content">
        <figure class="media-left">
            <p class="image is-64x64">
                <img src="{{ $medal->thumbnail_url }}" />
            </p>
        </figure>
        <div class="media-content">
            <div class="content">
                <strong>{{ $medal->name }}</strong>
                <i>{{ $medal->description }}</i>
            </div>
        </div>
    </div>
</section>
