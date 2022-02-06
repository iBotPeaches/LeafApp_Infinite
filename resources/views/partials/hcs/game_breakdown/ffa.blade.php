<?php
/** @var App\Models\Game $game */
?>
@foreach ($games as $game)
    <div class="box">
        <article class="media">
            <div class="media-left">
                <figure class="image is-96x96">
                    <img src="{{ $game->map->thumbnail_url }}" alt="{{ $game->map->name }}" />
                </figure>
            </div>
            <div class="media-content">
                <div class="content">
                    <p>
                        <strong>{{ $game->map->name }}</strong>
                        <br>
                        {{ $game->category->name }}
                    </p>
                </div>
            </div>
        </article>
    </div>
@endforeach
