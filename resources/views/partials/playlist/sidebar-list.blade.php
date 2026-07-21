<?php
    /** @var App\Models\Playlist[] $playlists */
    /** @var App\Models\Playlist $playlist */
?>
<article class="panel is-info">
    <p class="panel-heading">
        Playlists
    </p>
    @foreach ($playlists as $p)
        <a class="panel-block is-block <?= $p->id === $playlist->id ? 'has-background-link-soft' : ''; ?>"
           href="<?= route('playlist', [$p]); ?>"
        >
            {{ $p->name }}
        </a>
    @endforeach
</article>
