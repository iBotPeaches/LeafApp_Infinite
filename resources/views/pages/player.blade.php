@extends('layouts.app')
@section('title', $player->gamertag)
@section('description', $player->gamertag . ' Halo Infinite Stats')

@section('content')
    <div class="columns">
        <div class="column">
            <div class="card has-background-light">
                <div class="card-image">
                    <figure class="image is-4by3">
                        <img src="{{ $player->backdrop_url }}">
                    </figure>
                </div>
                <div class="card-content">
                    <div class="media">
                        <div class="media-left">
                            <figure class="image is-64x64">
                                <img src="{{ $player->emblem_url }}">
                            </figure>
                        </div>
                        <div class="media-content">
                            <p class="title is-4">{{ $player->gamertag }}</p>
                            <p class="subtitle is-6">{{ $player->service_tag }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-three-quarters">
            <livewire:game-history-table :player="$player" />
        </div>
    </div>
@endsection
