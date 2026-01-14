@extends('layouts.app')
@section('title', 'Leaf Scrims')
@section('description', 'Leaf Scrims')

@section('content')
    <div class="columns">
        <div class="column">
            <livewire:scrims-table /></livewire:scrims-table>
        </div>
    </div>
@endsection
