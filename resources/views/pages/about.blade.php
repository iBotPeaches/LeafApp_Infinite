@extends('layouts.app')
@section('title', 'Leaf - About')

@section('content')
    <div class="columns">
        <div class="column">
            <article class="message is-dark">
                <div class="message-header">
                    <p>What about the roadmap?</p>
                </div>
                <div class="message-body">
                    Right here on <a href="https://github.com/iBotPeaches/LeafApp_Infinite/issues">GitHub</a>.
                </div>
            </article>
            <article class="message is-dark">
                <div class="message-header">
                    <p>Feedback? Bugs?</p>
                </div>
                <div class="message-body">
                    The <a href="https://github.com/iBotPeaches/LeafApp_Infinite/issues">GitHub</a> will handle it all or
                    <a href="https://twitter.com/iBotPeaches">tweet me</a> or
                    <a target="_blank" href="https://discordapp.com/users/iBotPeaches#1569">discord me</a>.
                </div>
            </article>
            <div class="divider"></div>
            <article class="message is-dark">
                <div class="message-header">
                    <p>Why are some players missing in HCS?</p>
                </div>
                <div class="message-body">
                    They failed to put their correct gamertag in FaceIt. Blame them.
                </div>
            </article>
            <article class="message is-dark">
                <div class="message-header">
                    <p>What about old HCS tournaments?</p>
                </div>
                <div class="message-body">
                    In time we will retroactively pull that data in.
                </div>
            </article>
            <article class="message is-dark">
                <div class="message-header">
                    <p>What is "scrims" feature?</p>
                </div>
                <div class="message-body">
                    It allows logged-in users to specify custom games to combine for stats.
                </div>
            </article>
        </div>
        <div class="column">
            <article class="message is-dark">
                <div class="message-header">
                    <p>What powers my service record?</p>
                </div>
                <div class="message-body">
                    The current iteration have a toggle between PVP (All) or Ranked.
                </div>
            </article>
            <article class="message is-dark">
                <div class="message-header">
                    <p>My stats won't work!</p>
                </div>
                <div class="message-body">
                    You are probably not sharing matchmaking stats. Check the Accessibility tab.
                </div>
            </article>
            <div class="divider"></div>
            <article class="message is-dark">
                <div class="message-header">
                    <p>When do HCS games show up?</p>
                </div>
                <div class="message-body">
                    The instant FaceIt sends an API request to us.
                </div>
            </article>
            <article class="message is-dark">
                <div class="message-header">
                    <p>The games associated with a matchup are wrong!</p>
                </div>
                <div class="message-body">
                    Our system attempts to automatically find the matches in a matchup. Sometimes its wrong.
                </div>
            </article>
            <article class="message is-dark">
                <div class="message-header">
                    <p>What does Google login do?</p>
                </div>
                <div class="message-body">
                    Allows you to remember who you are as well as making scrims.
                </div>
            </article>
        </div>
    </div>
    <article class="message is-link">
        <div class="message-header">
            <p>Legal</p>
        </div>
        <div class="message-body">
            This application is offered by, which is solely responsible for its content. It is not sponsored or endorsed by Microsoft. This application uses HaloDotAPI, an unofficial, not sponsored or endorsed Halo API. All rights reserved. Microsoft, Halo, and the Halo Logo are trademarks of the Microsoft group of companies.
        </div>
    </article>
@endsection
