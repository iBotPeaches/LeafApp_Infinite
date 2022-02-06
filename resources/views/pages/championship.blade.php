@extends('layouts.app')
@section('title', $championship->name)
@section('description', $championship->name)

@section('content')
    <livewire:championship-bracket
        :championship="$championship"
        :bracket="$bracket"
        :round="$round"
    ></livewire:championship-bracket>
    <br />
    @include('partials.global.under_construction')
@endsection
