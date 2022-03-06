@extends('layouts.app')
@section('title', 'Leaf Scrim: ' . $scrim->id)
@section('description', 'Leaf Scrim:' . $scrim->id)

@section('content')
    <div class="columns">
        <div class="column">
            <h1 class="title">Scrim</h1>
            <h2 class="subtitle">
                {{ $scrim->games->count() }} games played
            </h2>
        </div>
        <div class="column is-four-fifths">
            @include('partials.scrim.navigation')
        </div>
    </div>
    @include('partials.scrim.tabs.' . $type, ['scrim' => $scrim])
@endsection
