@extends('layouts.app')
@section('title', $game->name)
@section('description', $game->description . ' Halo Infinite PCGR')

@section('content')
    <livewire:game-page :game="$game" />
@endsection
