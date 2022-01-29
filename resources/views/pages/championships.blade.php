@extends('layouts.app')
@section('title', 'Leaf - HCS Open Championships')
@section('description', 'Leaf - HCS Open Championships')

@section('content')
    <livewire:championships-table></livewire:championships-table>
    <br />
    <article class="message is-warning">
        <div class="message-header">
            <p>Under Development</p>
        </div>
        <div class="message-body">
            This feature is under development still. Feedback? Twitter or GitHub
        </div>
    </article>
@endsection
