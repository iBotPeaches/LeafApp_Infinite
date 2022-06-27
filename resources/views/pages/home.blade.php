@extends('layouts.app')
@section('title', 'Leaf')
@section('description', 'Leaf - Halo Infinite Stats')

@section('content')
    <section class="hero">
        <div class="hero-body">
            <h1 class="title">
                Leaf
            </h1>
            <h3 class="subtitle">
                Tracking Halo Infinite Stats
            </h3>
        </div>
    </section>
    <div class="columns">
        <div class="column">
            <livewire:add-gamer-form />
            @if ($medal)
                @include('partials.home.random_medal')
            @endif
            @include('partials.home.random_stat')
        </div>
        <div class="column">
            @include('partials.home.recently_updated')
        </div>
    </div>
@endsection
