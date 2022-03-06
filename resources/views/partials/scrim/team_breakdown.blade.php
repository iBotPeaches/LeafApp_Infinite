<article class="message is-{{ $color }}">
    <div class="message-body">
        <strong>{{ $team->name }}</strong>
        <span class="is-pulled-right">
            <span class="tag is-{{ $color }}">{{ $team->points }}</span>
        </span>
    </div>
</article>
@foreach ($team->players as $gamePlayer)
    <?php $player = $gamePlayer->player; ?>
    <div class="card has-background-{{ $color }}-light">
        <div class="card-content">
            <div class="media">
                <div class="media-left">
                    <figure class="image is-48x48">
                        <img src="{{ $player->emblem_url ?? '' }}">
                    </figure>
                </div>
                <div class="media-content">
                    <p class="title is-4">
                        <a href="{{ route('player', [$player]) }}">
                            {{ $player->gamertag ?? '' }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endforeach
