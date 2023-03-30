@if ($mode->count() === 0)
    <article class="message is-warning">
        <div class="message-header">
            <p>Uh oh</p>
        </div>
        <div class="message-body">
            Not enough ranked played to render :(
        </div>
    </article>
@else
    <table class="table is-striped is-hoverable is-fullwidth">
        <thead>
            <tr>
                <th>Map/Mode</th>
                <th>% {{ $title }}</th>
                <th>Total</th>
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
@endif
