<?php
/** @var App\Models\GamePlayer[] $gamePlayers */
/** @var App\Models\GameTeam $team */
$team = $gamePlayers->first()->team;
?>
<article class="panel {{ $team->color ?? 'is-dark' }}">
    <p class="panel-heading">
        {{ $team->name ?? 'Unknown' }}
    </p>
    <div class="table-container">
        <table class="table is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
            <tr>
                <th>Gamertag</th>
                <th>Kills</th>
                <th>Deaths</th>
                <th><abbr title="Kills / Deaths">KD</abbr></th>
                <th><abbr title="Kills + Assists / Deaths">KDA</abbr></th>
                <th><abbr title="Shots Hit / Shots Taken">Accuracy</abbr></th>
                <th><abbr title="Ordered by Points">Score</abbr></th>
                <th>Rank</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($gamePlayers as $gamePlayer)
                <tr>
                    <td>
                        <article class="media">
                            <figure class="media-left">
                                <p class="image is-32x32">
                                    <img src="{{ $gamePlayer->player->emblem_url }}">
                                </p>
                            </figure>
                            <div class="media-content">
                                <div class="content">
                                    <a href="{{ route('player', [$gamePlayer->player]) }}">
                                        {{ $gamePlayer->player->gamertag }}
                                    </a>
                                </div>
                            </div>
                        </article>
                    </td>
                    <td>{{ $gamePlayer->kills }}</td>
                    <td>{{ $gamePlayer->deaths }}</td>
                    <td class="{{ $gamePlayer->getKdColor() }}">
                        {{ $gamePlayer->kd }}
                    </td>
                    <td class="{{ $gamePlayer->getKdaColor() }}">
                        {{ $gamePlayer->kda }}
                    </td>
                    <td>{{ $gamePlayer->accuracy }}%</td>
                    <td>{{ $gamePlayer->score }}</td>
                    <td>{{ $gamePlayer->rank }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</article>
