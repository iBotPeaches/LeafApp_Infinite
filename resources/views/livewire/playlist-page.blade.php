<?php
    /** @var App\Models\Playlist $playlist */
?>
<div>
    <div class="notification is-light mb-2">
        {{ $playlist->description }}
    </div>
    <pre>
        {!! var_dump($playlist->rotations) !!}
    </pre>
</div>

