<?php
    /** @var App\Models\Playlist $playlist */
    /** @var App\Support\Rotations\RotationResult[] $rotations */
    /** @var bool $hasLastChange */
    /** @var \Illuminate\Support\Collection $rotationComparisons */
    /** @var \Illuminate\Support\Collection $mapComparisons */
    /** @var \Illuminate\Support\Collection $gametypeComparisons */
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
                    @if($hasLastChange)
                        <th>Change</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach ($rotations as $rotation)
                    @php
                        $comparison = $hasLastChange ? $rotationComparisons->get($rotation->combinedName) : null;
                        $status = $comparison ? $comparison['status'] : 'unchanged';
                        $weightChange = $comparison ? $comparison['weightChange'] : null;
                        $rowClass = '';

                        if ($status === 'new') {
                            $rowClass = 'has-background-success-light';
                        } elseif ($status === 'changed') {
                            $rowClass = 'has-background-warning-light';
                        }
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td>{{ $rotation->mapName }}</td>
                        <td>{{ $rotation->gametypeName }}</td>
                        <td>{{ number_format($rotation->weightPercent, 2) }}%</td>
                        @if($hasLastChange)
                            <td>
                                @if($status === 'new')
                                    <span class="tag is-success">New</span>
                                @elseif($status === 'changed' && $weightChange !== null)
                                    <span class="tag {{ $weightChange > 0 ? 'is-success' : 'is-danger' }}">
                                        {{ $weightChange > 0 ? '+' : '' }}{{ number_format($weightChange, 2) }}%
                                    </span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
                @if($hasLastChange)
                    @foreach($rotationComparisons as $combinedName => $comparison)
                        @if($comparison['status'] === 'removed')
                            @php
                                [$mapName, $gametypeName] = explode(' - ', $combinedName);
                            @endphp
                            <tr class="has-background-danger-light">
                                <td>{{ $mapName }}</td>
                                <td>{{ $gametypeName }}</td>
                                <td>0.00%</td>
                                <td><span class="tag is-danger">Removed</span></td>
                            </tr>
                        @endif
                    @endforeach
                @endif
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
                        'hasLastChange' => $hasLastChange,
                        'comparisons' => $mapComparisons
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
                        'hasLastChange' => $hasLastChange,
                        'comparisons' => $gametypeComparisons
                    ])
                </p>
            </article>
            @include('partials.leaderboard.common.next_refresh')

        </div>
    </div>
</div>
