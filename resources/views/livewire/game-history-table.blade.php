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
                <th><abbr title="Kills + Assists / Deaths">KDA</abbr></th>
                <th><abbr title="Shots Hit / Shots Taken">Accuracy</abbr></th>
                <th>Rank</th>
                <th>Date</th>
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
                    <td>{{ $game->map->name }}</td>
                    <td>{{ $game->category->name }}</td>
                    <td class="{{ $game->personal->getVictoryColor() }}">
                        {{ $game->personal->outcome->description }}
                        @if ($game->playlist->is_ranked)
                            @include('partials.player.game_outcome_icon')
                        @endif
                    </td>
                    <td>{{ $game->personal->kills }}</td>
                    <td>{{ $game->personal->deaths }}</td>
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
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $games->links() }}
</div>
