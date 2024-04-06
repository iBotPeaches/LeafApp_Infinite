<?php
    /** @var \App\Models\GamePlayer $gamePlayer */
?>
@foreach ($powerfulMedals as $medal)
    <article class="tile">
        <figure class="media-left">
            <p class="image is-48x48">
                <img src="{{ $medal['medal']->image }}" alt="{{ $medal['medal']->name }}"/>
            </p>
        </figure>
        <div class="media-content">
            <div class="content">
                <a href="{{ route('medalLeaderboard', $medal['medal']) }}" class="has-tooltip-arrow {{ $medal['medal']->tooltip_color }}"
                      data-tooltip="{{ $medal['medal']->difficulty->description }} - {{ $medal['medal']->description }}">

                    <strong style="white-space: nowrap" class="{{ $medal['medal']->text_color }}">
                        {{ $medal['medal']->name }}
                    </strong>
                </a>
                <div class="my-1 field is-grouped is-grouped-multiline">
                    @foreach ($medal['players'] as $gamePlayer)
                        <div class="control">
                            <div class="tags has-addons {{ $gamePlayer->player->is_cheater ? 'is-cheater' : '' }}">
                                <span class="tag">{{ $gamePlayer['medal_' . $medal['medal']->id] }}</span>
                                <a
                                    href="{{ route('player', [$gamePlayer->player]) }}"
                                    class="tag {{ $gamePlayer->team->color ?? 'is-dark' }} is-link"
                                >
                                    {{ $gamePlayer->player->gamertag }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </article>
@endforeach
