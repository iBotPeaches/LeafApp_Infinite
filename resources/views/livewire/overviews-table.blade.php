<?php
/** @var App\Models\Overview[]|\Illuminate\Support\Collection $overviews */
?>
<div>
    <h1 class="title">Map Overviews</h1>
    <h2 class="subtitle">An overview of a Map (versions, gametypes, players) in matchmaking.</h2>
    @foreach ($overviews->chunk(4) as $chunk)
        <div class="columns">
            @foreach ($chunk as $overview)
                <div class="column is-one-quarter">
                    <div class="card">
                        <a class="card-image" href="{{ route('overview', [$overview]) }}">
                            <figure class="image is-4by3">
                                <img src="{{ $overview->image }}" alt="Map image">
                            </figure>
                        </a>
                        <div class="card-content">
                            <div class="media">
                                <div class="media-content">
                                    <p class="title is-4">
                                        <a href="{{ route('overview', [$overview]) }}">
                                            {{ $overview->name }}
                                        </a>
                                    </p>
                                    <p class="subtitle is-6">
                                        @include('partials.player.date-link', ['date' => $overview->updated_at])
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @if ($chunk->count() < 4)
                @for ($i = 0; $i < 4 - $chunk->count(); $i++)
                    <div class="column is-one-quarter"></div>
                @endfor
            @endif
        </div>
    @endforeach
    {{ $overviews->links(data: ['scrollTo' => false]) }}
</div>
