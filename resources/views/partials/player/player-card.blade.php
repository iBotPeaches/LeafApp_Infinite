<div class="card has-background-light mb-2">
    <div class="card-image">
        <figure class="image is-4by3">
            <img src="{{ $player->backdrop_url }}">
        </figure>
    </div>
    <div class="card-content">
        <div class="media">
            <div class="media-left">
                <figure class="image is-64x64">
                    <img src="{{ $player->emblem_url }}">
                </figure>
            </div>
            <div class="media-content">
                <p class="title is-4">{{ $player->gamertag }}</p>
                <p class="subtitle is-6">{{ $player->service_tag }}</p>
            </div>
        </div>
    </div>
</div>
