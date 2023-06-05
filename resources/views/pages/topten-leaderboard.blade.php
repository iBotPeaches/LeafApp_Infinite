@extends('layouts.app')
@section('title', $analyticClass->title())
@section('description', $analyticClass->title() . ' Halo Infinite Leaderboard')

@section('content')
    @include('partials.leaderboard.topten.breadcrumbs')
    <livewire:top-ten-leaderboard :analyticKey="$analyticClass->key()"/>
@endsection
