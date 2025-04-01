<?php
use Illuminate\Support\Str;

/** @var App\Models\Game $game */
/** @var App\Models\GamePlayer[] $gamePlayers */
/** @var App\Models\GameTeam $team */
?>
<article class="panel {{ $team->color ?? 'is-dark' }}">
    <p class="panel-heading">
        @if ($team)
            <img class="is-16x16 image is-inline-block" src="{{ $team->emblem_url }}" alt="Emblem">
        @endif
        {{ $team->name ?? 'Players' }}
        @if ($game->playlist)
            <span class="is-pulled-right">
                @if ($team)
                    @if ($team->csr > 0)
                        <span class="has-tooltip-arrow" data-tooltip="Team Competitive Skill Rank">
                            <span class="is-hidden-mobile">CSR: </span>{{ number_format($team->csr, 2) }}
                        </span>
                    @endif
                    @if ($team->csr > 0 && $team->mmr)
                        |
                    @endif
                    @if ($team->mmr)
                        <span class="has-tooltip-arrow" data-tooltip="Team MatchMaking Ratio">
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
                @if ($game->is_ffa)
                    <th><abbr title="Team MatchMaking Ratio">MMR</abbr></th>
                @endif
                <th><abbr title="Kills">K</abbr></th>
                <th><abbr title="Deaths">D</abbr></th>
                <th><abbr title="Assists">A</abbr></th>
                <th><abbr title="Kills / Deaths">KD</abbr></th>
                <th><abbr title="Kills + (Assists * .3) / Deaths">KDA</abbr></th>
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
                                @if ($gamePlayer->player->is_bot)
                                    <span class="tag is-dark">BOT</span>
                                @elseif ($gamePlayer->player->is_cheater)
                                    <span class="tag is-danger">Banned</span>
                                @else
                                    <p class="image is-32x32">
                                        @include('partials.game.team_emblem_url')
                                    </p>
                                @endif
                            </figure>
                            <div class="media-content">
                                <div class="content" style="white-space: nowrap">
                                    @include('partials.links.player', ['player' => $gamePlayer->player])
                                </div>
                            </div>
                        </article>
                    </td>
                    @if ($game->playlist?->is_ranked)
                        <td>
                            <article class="media">
                                <figure class="media-left">
                                    <p class="image is-32x32 {{ 'is-' . Str::slug($gamePlayer->level) }}">
                                        <img src="{{ $gamePlayer->level_image }}" alt="{{ $gamePlayer->level }}"/>
                                    </p>
                                </figure>
                                <div class="media-content">
                                    <div class="content" style="white-space: nowrap;">
                                        @if ($gamePlayer->pre_csr > 0)
                                            <span
                                                class="has-tooltip-arrow {{ $gamePlayer->team->tooltip_color ?? '' }}"
                                                data-tooltip="CSR: {{ $gamePlayer->pre_csr }} ({{ $gamePlayer->csr_change }})"
                                            >
                                                @if ($gamePlayer->pre_csr > 1500)
                                                    <span class="has-text-weight-light">
                                                        {{ number_format($gamePlayer->pre_csr) }}
                                                    </span>
                                                @endif
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
                    @if ($game->is_ffa)
                        @if ($gamePlayer->mmr)
                            <td>{{ number_format($gamePlayer->mmr, 0) }}</td>
                        @else
                            <td>-</td>
                        @endif
                    @endif
                    <td class="{{ $gamePlayer->getExpectedKillsColor() }}">
                        @if (!is_null($gamePlayer->expected_kills))
                            @include('partials.game.expected_kills')
                        @else
                            {{ $gamePlayer->kills }}
                        @endif
                    </td>
                    <td class="{{ $gamePlayer->getExpectedDeathsColor() }}">
                        @if (!is_null($gamePlayer->expected_deaths))
                            @include('partials.game.expected_deaths')
                        @else
                            {{ $gamePlayer->deaths }}
                        @endif
                    </td>
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
