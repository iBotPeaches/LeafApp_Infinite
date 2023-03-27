<table class="table is-striped is-hoverable is-fullwidth">
    <thead>
        <tr>
            <th>Map/Mode</th>
            <th>% {{ $title }}</th>
            <th>Games Played</th>
        </tr>
    </thead>
    @foreach ($mode as $game)
        <tr>
            <td>
                {{ $game->map->name }} {{ $game->category->name }}
            </td>
            <td>{{ number_format($game->percentWon, 2) }}%</td>
            <td>{{ $game->summedTotal }}</td>
        </tr>
    @endforeach
</table>
