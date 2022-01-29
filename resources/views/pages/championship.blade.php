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
    <article class="message is-warning">
        <div class="message-header">
            <p>Under Development</p>
        </div>
        <div class="message-body">
            This feature is under development still. Feedback? Twitter or GitHub
        </div>
    </article>
@endsection
