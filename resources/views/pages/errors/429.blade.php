@extends('layouts.app')
@section('title', 'Leaf - Rate Limit')

@section('content')
    <article class="message is-danger">
        <div class="message-header">
            <p>Whoa whoa whoa</p>
        </div>
        <div class="message-body">
            I lose money on this site monthly. I pay $75/month ($40 server, $35 api access). This action you did
            takes a lot of resources on this server. Quite recently it has been hammered costing me more money.
            <br />
            <br />
            You are welcome to donate to help offset these costs. Leave me your gamertag, and you'll earn the <strong>Leaf badge</strong>
            <span class="tag is-success" data-tooltip="Donated via BuyMeACoffee" style="border-bottom: 0;">
                <i class="fas fa-leaf"></i>
            </span>
            on the site - <a href="https://www.buymeacoffee.com/iBotPeaches" target="_blank">(donation link)</a>.
            <br />
            <br />
            So in short - I have to rate limit it heavily to once per hour.
        </div>
    </article>
@endsection
