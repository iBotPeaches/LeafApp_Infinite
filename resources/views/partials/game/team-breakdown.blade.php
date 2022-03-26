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
                        @if (! $game->outdated)
                            <span class="is-pulled-right tag {{ $team->color }}">{{ $team->final_score ?? '?' }}</span>
                        @endif
                    </div>
                </div>
            </article>
        </div>
    </article>
@endforeach
