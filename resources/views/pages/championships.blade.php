@extends('layouts.app')
@section('title', 'Leaf - HCS Open Championships')
@section('description', 'Leaf - HCS Open Championships')

@section('content')
    <livewire:championships-table></livewire:championships-table>
    <br />
    @include('partials.global.under_construction')
@endsection
