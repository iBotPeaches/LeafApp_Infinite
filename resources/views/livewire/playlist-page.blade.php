<?php
    /** @var App\Models\Playlist $playlist */
    /** @var App\Support\Rotations\RotationResult[] $rotations */
?>
<div>
    <div class="notification is-light mb-2">
        {{ $playlist->description }}
    </div>
    <pre>
        {!! var_dump($rotations) !!}
    </pre>
</div>

