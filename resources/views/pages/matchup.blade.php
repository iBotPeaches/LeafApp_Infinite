@extends('layouts.app')
@section('title', $matchup->title)
@section('description', $matchup->description)

@section('content')
    <livewire:championship-matchup
        :championship="$championship"
        :matchup="$matchup"
    /></livewire:championship-matchup>
@endsection
