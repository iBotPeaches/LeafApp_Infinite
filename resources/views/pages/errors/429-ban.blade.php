@extends('layouts.app')
@section('title', 'Leaf - Rate Limit')

@section('content')
    <article class="message is-danger">
        <div class="message-header">
            <p>Whoa whoa whoa</p>
        </div>
        <div class="message-body">
            Checking for an account ban is not easy. So I have to restrict this endpoint.
            <br />
            <br />
            You are welcome to donate to help offset these costs. Leave me your gamertag, and you'll earn the <strong>Leaf badge</strong>
            <span class="tag is-success" data-tooltip="Donated via BuyMeACoffee" style="border-bottom: 0;">
                <i class="fas fa-leaf"></i>
            </span>
            on the site - <a href="https://www.buymeacoffee.com/iBotPeaches" target="_blank">(donation link)</a>.
            <br />
            <br />
            So I have to rate limit it heavily to five per day per logged-in user.
        </div>
    </article>
@endsection
