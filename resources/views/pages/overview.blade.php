@extends('layouts.app')
@section('title', $overview->name)
@section('description', $overview->name . ' Map Overview')

@section('content')
    <div class="columns">
        <div class="column">
            <livewire:overview-toggle-panel :overview="$overview" />
            <livewire:overview-card :overview="$overview" />
        </div>
        <div class="column is-three-quarters">
            content
        </div>
    </div>
@endsection
