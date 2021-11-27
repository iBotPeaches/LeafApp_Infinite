@extends('layouts.app')
@section('title', 'About')

@section('content')
    <article class="message is-link">
        <div class="message-header">
            <p>Legal</p>
        </div>
        <div class="message-body">
            This application is offered by, which is solely responsible for its content. It is not sponsored or endorsed by Microsoft. This application uses HaloDotAPI, an unofficial, not sponsored or endorsed Halo API. All rights reserved. Microsoft, Halo, and the Halo Logo are trademarks of the Microsoft group of companies.
        </div>
    </article>
    <div class="columns">
        <div class="column">
            <article class="message is-dark">
                <div class="message-header">
                    <p>What about customs?</p>
                </div>
                <div class="message-body">
                    Leaf is not interested in those. Only matchmade games.
                </div>
            </article>
            <article class="message is-dark">
                <div class="message-header">
                    <p>Feedback? Bugs?</p>
                </div>
                <div class="message-body">
                    The <a href="https://github.com/iBotPeaches/LeafApp_Infinite/issues">GitHub</a> will handle it all.
                </div>
            </article>
        </div>
        <div class="column">
            <article class="message is-dark">
                <div class="message-header">
                    <p>What about my full match history?</p>
                </div>
                <div class="message-body">
                    The current iteration of an unofficial API can only go 100 games in the past. Leaf records
                    these, so visit often to keep the database growing of your older games.
                </div>
            </article>
            <article class="message is-dark">
                <div class="message-header">
                    <p>My stats won't work!</p>
                </div>
                <div class="message-body">
                    You are probably not sharing match made stats. Check the Accessibility tab.
                </div>
            </article>
        </div>
    </div>
@endsection
