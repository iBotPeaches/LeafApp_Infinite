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
    @if ($scrim->is_complete)
        @include('partials.scrim.tabs.' . $type, ['scrim' => $scrim])
    @else
        <div class="notification is-info">
            This scrim is still processing. This is not yet automatic. So just refresh in a few seconds.
        </div>
    @endif
@endsection
