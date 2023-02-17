<?php
/** @var App\Models\Matchup[]|Illuminate\Support\Collection $matchups */
?>
<table class="table is-striped is-narrow is-hoverable is-fullwidth">
    <thead>
    <tr>
        <th>Matchup</th>
        <th>Winner</th>
        <th>Best Of</th>
        <th>Score</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($matchups as $matchup)
        <tr>
            <td>
                <a href="{{ route('matchup', [$championship, $matchup]) }}">
                    {{ $matchup->team1?->name }}
                </a>
                vs
                <a href="{{ route('matchup', [$championship, $matchup]) }}">
                    {{ $matchup->team2?->name }}
                </a>
            </td>
            <td>
                <a class="is-bold" href="{{ route('matchup', [$championship, $matchup]) }}">
                    {{ $matchup->winner?->name }}
                </a>
            </td>
            <td>
                {{ $matchup->best_of }}
            </td>
            <td>
                @if ($matchup->loser?->isBye())
                    -
                @else
                    {{ $matchup->score }}
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
