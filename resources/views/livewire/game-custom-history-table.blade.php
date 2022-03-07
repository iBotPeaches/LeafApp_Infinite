<?php
/** @var App\Models\Game[] $games */
?>
<div>
    <div class="table-container">
        <table class="table is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th>Map</th>
                    <th>Gametype</th>
                    <th>Result</th>
                    <th><abbr title="Kills">K</abbr></th>
                    <th><abbr title="Deaths">D</abbr></th>
                    <th><abbr title="Assists">A</abbr></th>
                    <th><abbr title="Kills / Deaths">KD</abbr></th>
                    <th><abbr title="Kills + Assists / Deaths">KDA</abbr></th>
                    <th><abbr title="Shots Hit / Shots Taken">Accuracy</abbr></th>
                    <th><abbr title="Ordered by Points">Score</abbr></th>
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
                            <abbr title="{{ $game->map->name }}">
                                {{ \Illuminate\Support\Str::limit($game->map->name, 15) }}
                            </abbr>
                        </a>
                    </td>
                    <td>{{ $game->category->name }}</td>
                    <td class="{{ $game->personal->getVictoryColor() }}">
                        {{ $game->personal->outcome->description }}
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
                    <td>{{ $game->personal->formatted_score }}</td>
                    <td>{{ $game->personal->rank }}</td>
                    <td>{{ $game->occurred_at->diffForHumans() }}</td>
                    @if ($isScrimEditor)
                        <td>
                            <input type="checkbox" wire:model="scrimGameIds" value="{{ $game->id }}">
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $games->links() }}
</div>
