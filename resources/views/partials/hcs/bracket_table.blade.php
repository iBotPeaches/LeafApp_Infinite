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
                {{ $matchup->winner->name }}
            </td>
            <td class="has-background-warning-light">
                {{ $matchup->loser->name }}
            </td>
            <td>
                {{ $matchup->best_of }}
            </td>
            <td>
                {{ $matchup->score }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
