<?php
    /** @var App\Models\Playlist $playlist */
?>
@extends('layouts.app')
@section('title', 'Leaf - ' . $playlist->name)
@section('description', 'Leaf - ' . $playlist->title)

@section('content')
    <div class="columns">
        <div class="column">
            @include('partials.playlist.current-playlist')
            @include('partials.playlist.sidebar-list')
        </div>
        <div class="column is-three-quarters">
            @include('partials.playlist.navigation')
            @include('partials.playlist.tabs.' . $type, ['playlist' => $playlist])
        </div>
    </div>
@endsection
