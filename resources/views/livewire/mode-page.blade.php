<div>
    <pre>
        @foreach ($best as $game)
            {{ $game->map->name }} {{ $game->category->name }}- {{ $game->total }}
        @endforeach
    </pre>
    <pre>
        @foreach ($worse as $game)
            {{ $game->map->name }} {{ $game->category->name }}- {{ $game->total }}
        @endforeach
    </pre>
</div>
