@extends('layouts.app')

@section('content')
    <section class="hero">
        <div class="hero-body">
            <p class="title">
                Leaf
            </p>
            <p class="subtitle">
                Tracking Halo Infinite Stats
            </p>
        </div>
    </section>
    <div class="columns">
        <div class="column">
            <livewire:add-gamer-form />
            @if ($medal)
                @include('partials.home.random_medal')
            @endif
        </div>
        <div class="column">
            @include('partials.home.recently_updated')
        </div>
    </div>
@endsection
