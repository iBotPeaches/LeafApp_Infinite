<?php
    /** @var App\Models\Playlist $playlist */
    /** @var App\Support\Rotations\RotationResult[] $rotations */
?>
<div>
    @include('partials.playlist.inactive-disclaimer')
    <div class="columns">
        <div class="column">
            <table class="table is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                <tr>
                    <th>Map</th>
                    <th>Gametype</th>
                    <th>Weight</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($rotations as $rotation)
                    <tr>
                        <td>{{ $rotation->mapName }}</td>
                        <td>{{ $rotation->gametypeName }}</td>
                        <td>{{ number_format($rotation->weightPercent, 2) }}%</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="column">
            <article class="panel is-info">
                <p class="panel-heading">
                    Map Breakdown
                </p>
                <p class="panel-block">
                    @include('partials.playlist.mode-breakdown', [
                        'items' => $maps, 
                        'title' => 'Maps',
                        'changes' => $rotationChanges['maps'] ?? null
                    ])
                </p>
            </article>
            <article class="panel is-info">
                <p class="panel-heading">
                    Gametype Breakdown
                </p>
                <p class="panel-block">
                    @include('partials.playlist.mode-breakdown', [
                        'items' => $gametypes, 
                        'title' => 'Gametypes',
                        'changes' => $rotationChanges['gametypes'] ?? null
                    ])
                </p>
            </article>
            
            @if($currentDate || $previousDate)
                <div class="notification is-light mb-2">
                    @if($currentDate)
                        <div><strong>Current rotation:</strong> <i>{{ $currentDate->format('M j, Y') }}</i></div>
                    @endif
                    @if($previousDate)
                        <div><strong>Previous rotation:</strong> <i>{{ $previousDate->format('M j, Y') }}</i></div>
                    @endif
                </div>
            @endif
            
            @include('partials.leaderboard.common.next_refresh')

        </div>
    </div>
</div>
