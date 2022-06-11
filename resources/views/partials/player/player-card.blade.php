@if ($player->is_bot)
    <div class="card has-background-success-light mb-2">
        <div class="card-content">
            <h1 class="title is-4">
                <span class="tag is-dark">BOT</span>&nbsp;{{ $player->gamertag }}
            </h1>
            <h3 class="subtitle is-6">{{ $player->service_tag }}</h3>
        </div>
    </div>
@else
    <div class="card has-background-success-light mb-2">
        <div class="card-image">
            <figure class="image is-4by3">
                <img src="{{ $player->backdrop_url }}" alt="{{ $player->gamertag }} Backdrop Emblem">
            </figure>
        </div>
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
@endif
