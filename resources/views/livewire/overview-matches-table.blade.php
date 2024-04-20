<?php
/** @var App\Models\Game[] $games */
?>
<div>
    <div class="table-container">
        <table class="table is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th>Playlist</th>
                    <th>Map</th>
                    <th>Gametype</th>
                    <th>Result</th>
                    <th>Date</th>
                    @if ($isScrimEditor)
                        <th>Add To</th>
                    @endif
                </tr>
            </thead>
            <tbody>
            @foreach ($games as $game)
                <tr>
                    <td>
                        <a href="{{ route('game', [$game]) }}">
                            <abbr title="{{ $game->playlist->name }}">
                                {{ \Illuminate\Support\Str::limit($game->playlist->name, 25) }}
                            </abbr>
                        </a>
                        @if ($game->playlist->is_ranked)
                            @include('partials.game.playlist_type', ['playlist' => $game->playlist])
                        @endif
                    </td>
                    <td>
                        <abbr title="{{ $game->map->name }}">
                            {{ \Illuminate\Support\Str::limit($game->map->shorthand, 15) }}
                        </abbr>
                    </td>
                    <td>
                        <abbr title="{{ $game->gamevariant?->name ?? $game->category?->name }}">
                            {{ \Illuminate\Support\Str::limit($game->gamevariant?->name ?? $game->category?->name, 18) }}
                        </abbr>
                    </td>
                    <td>

                    </td>
                    <td>
                        @include('partials.player.date-link', ['date' => $game->occurred_at])
                    </td>
                    @if ($isScrimEditor)
                        <td>
                            <input type="checkbox" wire:model.live="scrimGameIds" value="{{ $game->id }}">
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $games->links(data: ['scrollTo' => false]) }}
</div>
