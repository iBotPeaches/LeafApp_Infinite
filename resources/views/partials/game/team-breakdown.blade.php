<?php
/** @var App\Models\Game $game */
?>
@foreach ($game->teams as $team)
    <article class="message mb-3 {{ $team->color }}">
        <div class="message-body">
            <article class="media">
                <div class="media-left">
                    <figure class="image is-32x32">
                        <img src="{{ $team->emblem_url }}" alt="Image">
                    </figure>
                </div>
                <div class="media-content">
                    <div class="content">
                        <strong>{{ $team->name }}</strong>
                    </div>
                </div>
            </article>
        </div>
    </article>
@endforeach
