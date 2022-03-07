<?php
/** @var App\Models\Game $game */
/** @var App\Models\GamePlayer[] $gamePlayers */
/** @var App\Models\GameTeam $team */
?>
<article class="panel {{ $team->color ?? 'is-dark' }}">
    <p class="panel-heading">
        {{ $team->name ?? 'Players' }}
        @if ($game->playlist && $game->playlist->is_ranked)
            <span class="is-pulled-right">
                @if ($team)
                    <span class="has-tooltip-arrow" data-tooltip="Team Competitive Skill Rank">
                        <span class="is-hidden-mobile">CSR: </span>{{ number_format($team->csr, 2) }}
                    </span>
                    @if ($team->mmr)
                        | <span class="has-tooltip-arrow" data-tooltip="Team MatchMaking Ratio">
                            <span class="is-hidden-mobile">MMR: </span>{{ number_format($team->mmr, 2) }}
                        </span>
                    @endif
                @endif
            </span>
        @endif
    </p>
    <div class="table-container">
        <table class="table is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
            <tr>
                <th>Gamertag</th>
                @if ($game->playlist?->is_ranked)
                    <th>Level</th>
                @endif
                <th><abbr title="Kills">K</abbr></th>
                <th><abbr title="Deaths">D</abbr></th>
                <th><abbr title="Assists">A</abbr></th>
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
                                <div class="content" style="white-space: nowrap">
                                    <a href="{{ route('player', [$gamePlayer->player]) }}">
                                        {{ $gamePlayer->player->gamertag }}
                                    </a>
                                </div>
                            </div>
                        </article>
                    </td>
                    @if ($game->playlist?->is_ranked)
                        <td>
                            <article class="media">
                                <figure class="media-left">
                                    <p class="image is-32x32">
                                        <img src="{{ $gamePlayer->level_image }}" />
                                    </p>
                                </figure>
                                <div class="media-content">
                                    <div class="content" style="white-space: nowrap;">
                                        @if ($gamePlayer->pre_csr > 0)
                                            <span
                                                class="has-tooltip-arrow {{ $gamePlayer->team->tooltip_color ?? '' }}"
                                                data-tooltip="CSR: {{ $gamePlayer->pre_csr }} ({{ $gamePlayer->csr_change }})"
                                            >
                                                {{ $gamePlayer->level }}
                                            </span>
                                        @else
                                            {{ $gamePlayer->level }}
                                        @endif
                                    </div>
                                </div>
                            </article>
                        </td>
                    @endif
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
                        {{ $gamePlayer->accuracy }}%
                    </td>
                    <td>{{ $gamePlayer->formatted_score }}</td>
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
