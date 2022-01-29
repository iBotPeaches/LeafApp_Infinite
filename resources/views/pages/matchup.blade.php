@extends('layouts.app')
@section('title', $matchup->title)
@section('description', $matchup->description)

@section('content')
    <livewire:championship-matchup
        :championship="$championship"
        :matchup="$matchup"
    ></livewire:championship-matchup>
    <br />
    <article class="message is-warning">
        <div class="message-header">
            <p>Under Development</p>
        </div>
        <div class="message-body">
            This feature is under development still. Feedback? Twitter or GitHub
        </div>
    </article>
@endsection
