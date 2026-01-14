@extends('layouts.app')
@section('title', 'Top Ten Leaderboards')
@section('description', 'Top Ten Leaderboards')

@section('content')
    <livewire:top-ten-table /></livewire:top-ten-table>
    <article class="message is-dark">
        <div class="message-header">
            <p>Have an idea for a stat? Let me know</p>
        </div>
        <div class="message-body">
            Submit a <a
                target="_blank"
                href="https://github.com/iBotPeaches/LeafApp_Infinite/issues"
                rel="noreferrer">GitHub</a> issue,
            <a
                target="_blank"
                href="https://twitter.com/iBotPeaches"
                rel="noreferrer">tweet me</a> or
            <a
                target="_blank"
                href="https://discordapp.com/users/iBotPeaches#1569"
                rel="noreferrer">discord me</a>.
        </div>
    </article>
@endsection
