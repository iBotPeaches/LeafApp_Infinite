<?php
/** @var App\Models\Player $player */
?>
@if ($player->is_bot)
    <div class="card has-background-grey-light mb-2">
        <div class="card-content">
            <h1 class="title is-4">
                <span class="tag is-dark">BOT</span>&nbsp;{{ $player->gamertag }}
            </h1>
            <h3 class="subtitle is-6">{{ $player->service_tag }}</h3>
        </div>
    </div>
@else
    <div class="card {{ $player->is_cheater ? 'has-background-danger-light' : 'has-background-success-light' }} mb-2"
         style="background-image: url({{ $player->backdrop_url }}); background-repeat: no-repeat; background-position: center top;"
    >
        @if ($player->rank?->largeIcon)
            <div class="card-image pt-4">
                <figure class="image is-square">
                    <img src="{{ $player->rank->largeIcon }}" alt="{{ $player->gamertag }} Rank Image">
                </figure>
            </div>
        @endif
        <div class="card-content">
            <div class="media">
                <div class="media-left">
                    <figure class="image is-64x64">
                        <img src="{{ $player->emblem_url }}" alt="{{ $player->gamertag }} Emblem">
                    </figure>
                </div>
                <div class="media-content">
                    <h1 class="title is-4">{{ $player->gamertag }}</h1>
                    <h3 class="subtitle is-6">{{ $player->service_tag }}</h3>
                </div>
            </div>
        </div>
    </div>
    @if ($player->rank)
        <div class="notification has-text-centered">
            <span class="title is-6">
                {{ $player->rank->title }}
            </span>
            <div class="progress-wrapper pt-4">
                @if ($player->nextRank)
                    <p class="progress-value has-text-white pt-4">{{ $player->percentage_next_rank }}% to next rank.</p>
                @endif
                <progress
                    class="progress {{ $player->percentage_next_rank_color }}"
                    value="{{ $player->xp }}"
                    max="{{ $player->rank->threshold }}">
                </progress>
            </div>
        </div>
    @endif
@endif
