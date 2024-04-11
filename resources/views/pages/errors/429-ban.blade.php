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
            So I have to rate limit it heavily to five per day.
        </div>
    </article>
@endsection
