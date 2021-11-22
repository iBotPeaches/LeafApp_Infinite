@extends('layouts.app')
@section('title', $player->gamertag)
@section('description', $player->gamertag . ' Halo Infinite Stats')

@section('content')
    <div class="container mx-auto px-4 flex flex-col lg:flex-row">
        <div class="mt-6 lg:mt-0 lg:ml-6 lg:w-1/3 p-8" style="background-image: url('{{ $player->backdrop_url }}');">
            {{ $player->gamertag }}
            <img src="{{ $player->emblem_url }}" />
        </div>
        <div class="relative lg:w-2/3 p-8">
            <livewire:game-history-table :player="$player" />
        </div>
    </div>
@endsection
