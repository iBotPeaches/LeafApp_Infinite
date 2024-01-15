<?php
/** @var App\Models\Championship $championship */
/** @var App\Support\Bracket\BracketResult[] $summary */
?>
<article class="message is-warning">
    <div class="message-header">
        <p>Under Development</p>
    </div>
    <div class="message-body">
        The point system utilized in API does not reflect true point system used - thus under development.
    </div>
</article>


<div class="table-container">
    <table class="table is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
        <tr>
            <th>Team</th>
            <th>Record</th>
            <!--<th><abbr title="Sum of team's opponent(s) wins.">Buchholz</abbr></th>-->
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
</div>
