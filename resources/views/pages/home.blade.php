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
        <div class="column is-half">
            <livewire:add-gamer-form />
        </div>
        <div class="column">

        </div>
        <div class="column">

        </div>
    </div>
@endsection
