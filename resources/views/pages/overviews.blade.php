@extends('layouts.app')
@section('title', 'Map Overviews')
@section('description', 'Halo Infinite Map Overviews')

@section('content')
    <livewire:overviews-table :type="$type" /></livewire:overviews-table>
@endsection
