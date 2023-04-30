@extends('layouts.app')
@section('title', 'Leaf - Rate Limit')

@section('content')
    <article class="message is-danger">
        <div class="message-header">
            <p>Whoa whoa whoa</p>
        </div>
        <div class="message-body">
            Listen, I lose money on this site monthly. I pay $75/month ($40 server, $35 api access). This action you did
            takes a lot of resources on this server. Quite recently it has been hammered costing me more money.
            <br />
            <br />
            So I have to rate limit it heavily to once per hour.
        </div>
    </article>
@endsection
