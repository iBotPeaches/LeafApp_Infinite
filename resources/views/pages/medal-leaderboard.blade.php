@extends('layouts.app')
@section('title', $medal->name)
@section('description', $medal->name . ' Halo Infinite Leaderboard')

@section('content')
    <div class="columns">
        <div class="column">
            @include('partials.leaderboard.medal.breadcrumbs')
            <livewire:player-toggle-panel />
            @include('partials.leaderboard.medal.medal-card')
            @include('partials.leaderboard.medal.notice')
        </div>
        <div class="column is-three-quarters">
            <livewire:medals-leaderboard :medal="$medal" />
        </div>
    </div>
@endsection
