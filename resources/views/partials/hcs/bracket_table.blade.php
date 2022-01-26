<?php
/** @var App\Models\Matchup[]|Illuminate\Support\Collection $matchups */
?>
<table class="table is-striped is-narrow is-hoverable is-fullwidth">
    <thead>
    <tr>
        <th>Winner</th>
        <th>Loser</th>
        <th>Best Of</th>
        <th>Score</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($matchups as $matchup)
        <tr>
            <td class="has-background-success-light">
                <a href="{{ route('matchup', [$championship, $matchup]) }}">
                    {{ $matchup->winner->name }}
                </a>
            </td>
            <td class="has-background-warning-light">
                <a href="{{ route('matchup', [$championship, $matchup]) }}">
                    {{ $matchup->loser->name }}
                </a>
            </td>
            <td>
                {{ $matchup->best_of }}
            </td>
            <td>
                @if ($matchup->loser->isBye())
                    -
                @else
                    {{ $matchup->score }}
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
