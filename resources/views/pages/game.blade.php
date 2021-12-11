@extends('layouts.app')
@section('title', $game->title)
@section('description', $game->description . ' Halo Infinite PCGR')

@section('content')
    <livewire:game-page :game="$game"></livewire:game-page>
@endsection
