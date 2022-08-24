@extends('layouts.app')
@section('title', $player->gamertag . ' - Halo Infinite Stats')

@section('content')
    <div class="columns">
        <div class="column">
            @if (in_array($type, ['overview', 'medals', 'competitive']))
                <livewire:player-toggle-panel :type="$type" />
            @endif
            @include('partials.player.player-card')
            @if (in_array($type, ['matches', 'custom', 'lan']))
                <div class="notification">
                    <a class="is-small" href="{{ route('historyCsv', [$player, $type]) }}">export to csv</a>

                    @if (in_array($type, ['custom']))
                        <livewire:scrim-toggle-panel></livewire:scrim-toggle-panel>
                    @endif
                </div>
            @endif
            @if ($player->is_private)
                <div class="notification is-warning">
                    <i class="fas fa-exclamation-triangle"></i> Account Private
                </div>
            @endif
            @if ($player->is_cheater)
                <div class="notification is-danger">
                    <i class="fas fa-exclamation-triangle"></i> Flagged as Cheater
                </div>
            @endif
            @if (!config('services.autocode.disabled'))
                @if (!$player->is_bot)
                    <livewire:update-player-panel :player="$player" :type="$type" />
                @endif
            @endif
            @if ($type === 'competitive')
                <div class="notification is-warning">
                    343 does not like mid-season CSR resets - it causes some issues with data.
                </div>
            @endif
            @auth
                @include('partials.player.linkable-card')
            @endauth
        </div>
        <div class="column is-three-quarters">
            @include('partials.player.navigation')
            @include('partials.player.tabs.' . $type, ['player' => $player])
        </div>
    </div>
@endsection
