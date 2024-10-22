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
                    The <a
                        target="_blank"
                        href="https://github.com/iBotPeaches/LeafApp_Infinite/issues"
                        rel="noreferrer">GitHub</a> will handle it all or
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
            <article class="message is-dark">
                <div class="message-header">
                    <p>What about all my seasons combined?</p>
                </div>
                <div class="message-body">
                    This is now available. "Merged" Season view includes current + old seasons combined.
                </div>
            </article>
            <div class="divider"></div>
            <article class="message is-dark">
                <div class="message-header">
                    <p>Why are some players missing in HCS?</p>
                </div>
                <div class="message-body">
                    They failed to put their correct gamertag in FaceIt.
                </div>
            </article>
            <article class="message is-dark">
                <div class="message-header">
                    <p>How do I get a "Donator" badge?</p>
                </div>
                <div class="message-body">
                    The coffee icon in top right - any amount if you leave your gamertag will obtain it.
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
                    The current iteration have a toggle between PVP (All) or Ranked as well as Season.
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
            <article class="message is-dark">
                <div class="message-header">
                    <p>MMR/CSR is missing / "wrong" from this game.</p>
                </div>
                <div class="message-body">
                    This sucks I know, but it seems its all on 343's APIs for this. Nothing I can fix.
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
                    Our system attempts to find the matches in a match-up. Sometimes its wrong.
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
            This application is offered by, which is solely responsible for its content. It is not sponsored or endorsed by Microsoft. This application uses dotapi.gg, an unofficial, not sponsored or endorsed Halo API. All rights reserved. Microsoft, Halo, and the Halo Logo are trademarks of the Microsoft group of companies.
        </div>
    </article>
@endsection
