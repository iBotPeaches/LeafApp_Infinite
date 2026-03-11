<div>
    @if ($historicRotations->isEmpty())
        <article class="message is-info">
            <div class="message-body">
                No historic rotation data is available for this playlist.
            </div>
        </article>
    @else
        @foreach ($historicRotations as $index => $entry)
            <article class="panel {{ $index === 0 ? 'is-info' : 'is-light' }}">
                <p class="panel-heading">
                    {{ $entry['date']->toFormattedDateString() }}
                    @if ($index === 0)
                        <span class="tag is-success is-light ml-2">Current</span>
                    @endif
                </p>
                <div class="panel-block is-block">
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
                                @foreach ($entry['rotations'] as $rotation)
                                    <tr>
                                        <td>{{ $rotation->mapName }}</td>
                                        <td>{{ $rotation->gametypeName }}</td>
                                        <td>{{ number_format($rotation->weightPercent, 2) }}%</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="column is-narrow">
                            <strong>Maps</strong>
                            <table class="table is-striped is-narrow is-hoverable is-fullwidth mb-3">
                                <tbody>
                                @foreach ($entry['maps'] as $name => $value)
                                    <tr>
                                        <td>{{ $name }}</td>
                                        <td>{{ number_format($value) }}%</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <strong>Gametypes</strong>
                            <table class="table is-striped is-narrow is-hoverable is-fullwidth">
                                <tbody>
                                @foreach ($entry['gametypes'] as $name => $value)
                                    <tr>
                                        <td>{{ $name }}</td>
                                        <td>{{ number_format($value) }}%</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </article>
        @endforeach
    @endif
</div>
