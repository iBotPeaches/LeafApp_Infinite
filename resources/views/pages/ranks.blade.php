<?php
/** @var App\Models\Player $player */
?>
@extends('layouts.app')
@section('title', 'Leaf - Ranks')
@section('description', 'Leaf - Ranks')

@section('content')
    <div class="columns">
        <div class="column">
            <livewire:ranks-page /></livewire:ranks-page>
        </div>
    </div>
@endsection
