<?php
/** @var App\Models\Matchup[]|Illuminate\Support\Collection $matchups */
?>
<table class="table is-striped is-narrow is-hoverable is-fullwidth">
    <thead>
    <tr>
        <th>1<sup>st</sup></th>
        <th>2<sup>nd</sup></th>
        <th>3<sup>rd</sup></th>
        <th>4<sup>th</sup></th>
    </tr>
    </thead>
    <tbody>
    @foreach ($matchups as $matchup)
        <tr>
            <td>
                <a href="{{ route('matchup', [$championship, $matchup]) }}">
                    {{ $matchup->getTeamAt(1)?->name }}
                </a>
            </td>
            <td>
                <a href="{{ route('matchup', [$championship, $matchup]) }}">
                    {{ $matchup->getTeamAt(2)?->name }}
                </a>
            </td>
            <td>
                <a href="{{ route('matchup', [$championship, $matchup]) }}">
                    {{ $matchup->getTeamAt(3)?->name }}
                </a>
            </td>
            <td>
                <a href="{{ route('matchup', [$championship, $matchup]) }}">
                    {{ $matchup->getTeamAt(4)?->name }}
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
