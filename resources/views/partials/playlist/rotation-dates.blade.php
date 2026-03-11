<?php
    /** @var App\Models\Playlist $playlist */
    $rotationChanges = $playlist->changes()->orderByDesc('created_at')->limit(2)->get();
    $currentRotation = $rotationChanges->first();
    $previousRotation = $rotationChanges->count() > 1 ? $rotationChanges->last() : null;
?>
@if ($currentRotation)
    <article class="panel is-info mt-2">
        <p class="panel-heading">
            Rotation Versions
        </p>
        <div class="panel-block is-block">
            <p>
                <strong>Current:</strong>
                <span class="tag is-info is-light">{{ $currentRotation->created_at->toFormattedDateString() }}</span>
            </p>
            @if ($previousRotation)
                <p class="mt-1">
                    <strong>Previous:</strong>
                    <span class="tag is-light">{{ $previousRotation->created_at->toFormattedDateString() }}</span>
                </p>
            @endif
        </div>
    </article>
@endif
