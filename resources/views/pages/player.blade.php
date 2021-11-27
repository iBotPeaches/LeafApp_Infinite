@extends('layouts.app')
@section('title', $player->gamertag)
@section('description', $player->gamertag . ' Halo Infinite Stats')

@section('content')
    <div class="columns">
        <div class="column">
            @include('partials.player.player-card')
            <livewire:update-player-panel :player="$player" :type="$type" />
            @if ($player->is_private)
                <div class="notification is-warning">
                    <i class="fas fa-exclamation-triangle"></i> Account Private
                </div>
            @endif
        </div>
        <div class="column is-three-quarters">
            @include('partials.player.navigation')
            @include('partials.player.tabs.' . $type, ['player' => $player])
        </div>
    </div>
@endsection
