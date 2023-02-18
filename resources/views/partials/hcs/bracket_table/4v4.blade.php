<?php
/** @var App\Models\Matchup[]|Illuminate\Support\Collection $matchups */
?>
<table class="table is-striped is-narrow is-hoverable is-fullwidth">
    <thead>
    <tr>
        <th>Team 1</th>
        <th>Team 2</th>
        <th>Winner</th>
        <th>Score</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($matchups as $matchup)
        <tr>
            <td style="opacity: {{ $matchup->team1?->id === $matchup->loser?->id ? '40%' : '100%' }}">
                <article class="media">
                    <figure class="media-left">
                        <p class="image is-32x32">
                            <img class="is-rounded" src="{{ $matchup->team1?->avatar }}" alt="{{ $matchup->team1?->name }} "/>
                        </p>
                    </figure>
                    <div class="media-content">
                        <div class="content" style="white-space: nowrap;">
                            <a href="{{ route('matchup', [$championship, $matchup]) }}">
                                {{ $matchup->team1?->name }}
                            </a>
                        </div>
                    </div>
                </article>
            </td>
            <td style="opacity: {{ $matchup->team2?->id === $matchup->loser?->id ? '40%' : '100%' }}">
                <article class="media">
                    <figure class="media-left">
                        <p class="image is-32x32">
                            <img class="is-rounded" src="{{ $matchup->team2?->avatar }}" alt="{{ $matchup->team2?->name }} "/>
                        </p>
                    </figure>
                    <div class="media-content">
                        <div class="content" style="white-space: nowrap;">
                            <a href="{{ route('matchup', [$championship, $matchup]) }}">
                                {{ $matchup->team2?->name }}
                            </a>
                        </div>
                    </div>
                </article>
            </td>
            <td>
                <article class="media">
                    <figure class="media-left">
                        <p class="image is-32x32">
                            <img class="is-rounded" src="{{ $matchup->winner?->avatar }}" alt="{{ $matchup->winner?->name }} "/>
                        </p>
                    </figure>
                    <div class="media-content">
                        <div class="content" style="white-space: nowrap;">
                            <a class="is-bold" href="{{ route('matchup', [$championship, $matchup]) }}">
                                {{ $matchup->winner?->name }}
                            </a>
                        </div>
                    </div>
                </article>
            </td>
            <td>
                @if ($matchup->loser?->isBye())
                    -
                @else
                    {{ $matchup->score }}
                @endif
            </td>
            <td>
                @if ($matchup->started_at && $matchup->ended_at)
                    <span
                        class="has-tooltip-arrow"
                        data-tooltip="Length: {{ $matchup->length }}"
                    >
                        @include('partials.player.date-link', ['date' => $matchup->ended_at])
                    </span>
                @else
                    <i>In Progress</i>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
