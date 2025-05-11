<?php
    /** @var App\Models\Playlist $playlist */
?>
@if ($playlist->is_active)
    <div class="notification is-light mb-2">
        {{ $playlist->description }}
    </div>
@else
    <div class="notification is-warning">
        This playlist is currently inactive. 343 removes the hopper (playlist weighting) when the playlist is inactive.
    </div>
@endif
