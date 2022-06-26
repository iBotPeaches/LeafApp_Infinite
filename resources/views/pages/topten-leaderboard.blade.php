@extends('layouts.app')
@section('title', $analyticClass->title())
@section('description', $analyticClass->title() . ' Halo Infinite Leaderboard')

@section('content')
    <div class="columns">
        <div class="column">
            @include('partials.leaderboard.topten.breadcrumbs')
            @include('partials.leaderboard.topten.analytic-card')
            @include('partials.leaderboard.common.notice')
        </div>
        <div class="column is-two-thirds">
            <livewire:top-ten-leaderboard :analyticKey="$analyticClass->key()"/>
        </div>
    </div>
@endsection
