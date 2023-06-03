<?php
/** @var App\Models\Championship $championship */
/** @var App\Support\Bracket\BracketResult[] $summary */
?>
<table class="table is-striped is-narrow is-hoverable is-fullwidth">
    <thead>
    <tr>
        <th>Team</th>
        <th></th>
        <th><abbr title="Points">Pts</abbr></th>
    </tr>
    </thead>
    <tbody>
    @foreach ($summary as $team)
        <tr>
            <td>{{ $team->matchupTeam->name }}</td>
            <td>{{ $team->wins }}-{{ $team->losses }}</td>
            <td>{{ $team->points }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
