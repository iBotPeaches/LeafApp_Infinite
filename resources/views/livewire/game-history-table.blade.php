<?php
/** @var App\Models\Game[] $games */
?>
<div>
    <div class="table-container">
        <table class="table is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
            <tr>
                <th>Playlist</th>
                <th>Map</th>
                <th>Gametype</th>
                <th>Result</th>
                <th><abbr title="Kills">K</abbr></th>
                <th><abbr title="Deaths">D</abbr></th>
                <th><abbr title="Assists">A</abbr></th>
                <th><abbr title="Kills / Deaths">KD</abbr></th>
                <th><abbr title="Kills + (Assists * .3) / Deaths">KDA</abbr></th>
                <th><abbr title="Shots Hit / Shots Taken">Accuracy</abbr></th>
                <th>Rank</th>
                <th>Date</th>
                @if ($isScrimEditor)
                    <th>Add To</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach ($games as $game)
                <tr>
                    <td>
                        <a href="{{ route('game', [$game]) }}">
                            <abbr title="{{ $game->playlist->name }}">
                                {{ \Illuminate\Support\Str::limit($game->playlist->name, 15) }}
                            </abbr>
                        </a>
                        @if ($game->playlist->is_ranked)
                            @include('partials.game.playlist_type', ['playlist' => $game->playlist])
                        @endif
                    </td>
                    <td>
                        <abbr title="{{ $game->map->name }}">
                            {{ \Illuminate\Support\Str::limit($game->map->shorthand, 15) }}
                        </abbr>
                    </td>
                    <td>
                        <abbr title="{{ $game->gamevariant?->name ?? $game->category?->name }}">
                            {{ \Illuminate\Support\Str::limit($game->gamevariant?->name ?? $game->category?->name, 18) }}
                        </abbr>
                    </td>
                    <td class="{{ $game->personal->getVictoryColor() }}">
                        {{ $game->personal->outcome->description }}
                        @if ($game->playlist->is_ranked)
                            @include('partials.player.game_outcome_icon')
                        @endif
                    </td>
                    <td class="{{ $game->personal->getExpectedKillsColor() }}">
                        @if (!is_null($game->personal->expected_kills))
                            @include('partials.game.expected_kills', ['gamePlayer' =>  $game->personal])
                        @else
                            {{ $game->personal->kills }}
                        @endif
                    </td>
                    <td class="{{ $game->personal->getExpectedDeathsColor() }}">
                        @if (!is_null($game->personal->expected_deaths))
                            @include('partials.game.expected_deaths', ['gamePlayer' =>  $game->personal])
                        @else
                            {{ $game->personal->deaths }}
                        @endif
                    </td>
                    <td>{{ $game->personal->assists }}</td>
                    <td class="{{ $game->personal->getKdColor() }}">
                        {{ $game->personal->kd }}
                    </td>
                    <td class="{{ $game->personal->getKdaColor() }}">
                        {{ $game->personal->kda }}
                    </td>
                    <td class="has-background-{{ $game->personal->accuracy_color }}-light">
                        {{ $game->personal->accuracy }}%
                    </td>
                    <td>{{ $game->personal->rank }}</td>
                    <td>
                        @include('partials.player.date-link', ['date' => $game->occurred_at])
                    </td>
                    @if ($isScrimEditor)
                        <td>
                            <input type="checkbox" wire:model.live="scrimGameIds" value="{{ $game->id }}">
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $games->links(data: ['scrollTo' => false]) }}
</div>
