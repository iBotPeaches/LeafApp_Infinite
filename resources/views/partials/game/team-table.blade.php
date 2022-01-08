<?php
/** @var App\Models\Game $game */
/** @var App\Models\GamePlayer[] $gamePlayers */
/** @var App\Models\GameTeam $team */
$team = $gamePlayers->first()->team;
?>
<article class="panel {{ $team->color ?? 'is-dark' }}">
    <p class="panel-heading">
        {{ $team->name ?? 'Players' }}
        @if ($game->playlist->is_ranked)
            <span class="is-pulled-right">
                <abbr title="Avg. Team CSR">{{ number_format($gamePlayers->avg('pre_csr'), 2) }}</abbr>
            </span>
        @endif
    </p>
    <div class="table-container">
        <table class="table is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
            <tr>
                <th>Gamertag</th>
                @if ($game->playlist->is_ranked)
                    <th>Level</th>
                @endif
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
            @foreach ($gamePlayers->sortBy('rank') as $gamePlayer)
                <tr>
                    <td>
                        <article class="media">
                            <figure class="media-left">
                                <p class="image is-32x32">
                                    @include('partials.game.team_emblem_url')
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
                    @if ($game->playlist->is_ranked)
                        <td>
                            @if ($gamePlayer->pre_csr > 0)
                                <abbr title="CSR: {{ $gamePlayer->pre_csr }} ({{ $gamePlayer->csr_change }})">
                                    {{ $gamePlayer->level }}
                                </abbr>
                            @else
                                {{ $gamePlayer->level }}
                            @endif
                        </td>
                    @endif
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
                    <td>
                        {{ $gamePlayer->rank }}
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
</article>
