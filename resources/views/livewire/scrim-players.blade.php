<div class="table-container">
    <table class="table is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
        <tr>
            <th>Gamertag</th>
            <th><abbr title="Kills">K</abbr></th>
            <th><abbr title="Deaths">D</abbr></th>
            <th><abbr title="Assists">A</abbr></th>
            <th><abbr title="Kills / Deaths">KD</abbr></th>
            <th><abbr title="Kills + Assists / Deaths">KDA</abbr></th>
            <th><abbr title="Shots Hit / Shots Taken">Accuracy</abbr></th>
            <th><abbr title="Damage Dealt">Dmg. Dealt</abbr></th>
            <th><abbr title="Damage Taken">Dmg. Taken</abbr></th>
            <th><abbr title="Ordered by Points">Avg Score</abbr></th>
            <th>Avg Rank</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($gamePlayers as $gamePlayer)
            <tr>
                <td>
                    <article class="media">
                        <figure class="media-left">
                            <p class="image is-32x32">
                                @include('partials.game.team_emblem_url')
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content" style="white-space: nowrap">
                                <a href="{{ route('player', [$gamePlayer->player]) }}">
                                    {{ $gamePlayer->player->gamertag }}
                                </a>
                            </div>
                        </div>
                    </article>
                </td>
                <td>{{ $gamePlayer->kills }}</td>
                <td>{{ $gamePlayer->deaths }}</td>
                <td>{{ $gamePlayer->assists }}</td>
                <td class="{{ $gamePlayer->getKdColor() }}">
                    {{ $gamePlayer->kd }}
                </td>
                <td class="{{ $gamePlayer->getKdaColor() }}">
                    {{ $gamePlayer->kda }}
                </td>
                <td class="has-background-{{ $gamePlayer->accuracy_color }}-light">
                    {{ number_format($gamePlayer->accuracy, 2) }}%
                </td>
                <td>
                    {{ number_format($gamePlayer->damageDealt, 0) }}
                </td>
                <td>
                    {{ number_format($gamePlayer->damageTaken, 0) }}
                </td>
                <td>{{ $gamePlayer->formatted_score }}</td>
                <td>
                    {{ number_format($gamePlayer->rank, 1) }}
                    @if (!$gamePlayer->was_at_end)
                        <span class="tag is-danger">
                            <abbr title="Did Not Finish (Quit/Crashed)">DNF</abbr>
                        </span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
