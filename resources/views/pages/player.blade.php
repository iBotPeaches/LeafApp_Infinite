@extends('layouts.app')
@section('title', $player->gamertag . ' - Halo Infinite Stats')

@section('content')
    <div class="columns">
        <div class="column">
            @if (in_array($type, ['overview', 'medals', 'competitive', 'modes']))
                <livewire:player-toggle-panel :type="$type" />
            @endif
            <livewire:player-card :player="$player" />
            @if (in_array($type, ['matches', 'custom', 'lan']))
                <div class="notification">
                    <a class="is-small" href="{{ route('historyCsv', [$player, $type]) }}" rel="nofollow">export to csv</a>

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
            @if ($player->is_botfarmer)
                <div class="notification is-info">
                    <i class="fas fa-robot"></i>
                    <span class="has-tooltip-arrow" data-tooltip="Match history is at least 50% Bot Bootcamp and thus excluded from leaderboards.">
                        Flagged as Bot Farmer
                    </span>
                </div>
            @endif
            @if (!config('services.halodotapi.disabled'))
                @if (!$player->is_bot)
                    <livewire:update-player-panel :player="$player" :type="$type" />
                @endif
            @endif
            <livewire:player-badges :player="$player" />
            @auth
                @include('partials.player.linkable-card')
            @endauth
        </div>
        <div class="column is-three-quarters">
            @include('partials.player.navigation')
            @include('partials.player.ban-header')
            @include('partials.player.tabs.' . $type, ['player' => $player])
        </div>
    </div>
@endsection
