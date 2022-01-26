@extends('layouts.app')
@section('title', 'HCS Matchup TODO')
@section('description', 'HCS Matchup TODO')

@section('content')
    <livewire:championship-matchup
        :championship="$championship"
        :matchup="$matchup"
    ></livewire:championship-matchup>
@endsection
