<div>
    <table class="table table-auto">
        <thead>
            <tr>
                <th>Mode</th>
                <th>Map</th>
                <th>Gametype</th>
                <th>Outcome</th>
                <th>Kills</th>
                <th>Deaths</th>
                <th>KD</th>
                <th>KDA</th>
                <th>Accuracy</th>
                <th>Score</th>
                <th>Rank</th>
                <th>Occurred At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($games as $game)
                <tr>
                    <td>{{ $game->experience->description }}</td>
                    <td>{{ $game->map->name }}</td>
                    <td>{{ $game->category->name }}</td>
                    <td>{{ $game->personal->outcome->description }}</td>
                    <td>{{ $game->personal->kills }}</td>
                    <td>{{ $game->personal->deaths }}</td>
                    <td>{{ $game->personal->kd }}</td>
                    <td>{{ $game->personal->kda }}</td>
                    <td>{{ $game->personal->accuracy }}</td>
                    <td>{{ $game->personal->score }}</td>
                    <td>{{ $game->personal->rank }}</td>
                    <td>{{ $game->occurred_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $games->links() }}
</div>
