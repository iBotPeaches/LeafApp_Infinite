<?php
/** @var App\Models\Analytic[] $stats */
?>
<div>
    <h1 class="title">Top Ten Leaderboards</h1>
    <h2 class="subtitle">A collection of random stats that updates once every other day.</h2>
    <div class="table-container">
        <table class="table is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th>Stat</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($stats as $stat)
                <tr>
                    <td>
                        <a href="{{ route('topTenLeaderboard', ['key' => $stat->key]) }}">
                            {{ $stat->stat->title() }}
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $stats->links(data: ['scrollTo' => false]) }}
</div>
