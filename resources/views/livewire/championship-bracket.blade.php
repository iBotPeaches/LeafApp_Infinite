<?php
/** @var App\Models\Championship $championship */
?>
<div>
    <div class="columns">
        <div class="column">
            <div class="card">
                <div class="card-content">
                    <p class="title">
                        {{ $championship->name }}
                        <span class="tag is-black">
                            {{ $championship->started_at->toFormattedDateString() }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
        <div class="column">
            @include('partials.hcs.navigation')
        </div>
    </div>
    <div class="columns">
        @if ($bracket === App\Enums\Bracket::RULES)
            <div class="box">
                <x-markdown class="content">
                    {{ $championship->description }}
                </x-markdown>
            </div>
        @else
            <div class="column is-one-fifth">
                @include('partials.hcs.bracket_panel')
                <article class="message is-link">
                    <div class="message-body">
                        View on <a target="_blank" href="{{ $championship->faceitUrl }}" rel="nofollow">FaceIt</a>
                    </div>
                </article>
            </div>
            <div class="column">
                @include('partials.hcs.bracket_table')
            </div>
        @endif
    </div>
</div>
