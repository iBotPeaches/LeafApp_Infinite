@extends('layouts.app')
@section('title', $player->gamertag)
@section('description', $player->gamertag . ' Halo Infinite Stats')

@section('content')
    <div class="columns">
        <div class="column">
            <livewire:player-toggle-panel />
            @include('partials.player.player-card')
            @if (in_array($type, ['matches', 'custom']))
                <div class="notification">
                    <a class="is-small" href="{{ route('historyCsv', [$player]) }}">export to csv</a>
                </div>
            @endif
            @if ($player->is_private)
                <div class="notification is-warning">
                    <i class="fas fa-exclamation-triangle"></i> Account Private
                </div>
            @endif
            <livewire:update-player-panel :player="$player" :type="$type" />
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
