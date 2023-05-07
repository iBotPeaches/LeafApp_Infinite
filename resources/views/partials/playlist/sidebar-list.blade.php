<?php
    /** @var App\Models\Playlist[] $playlists */
    /** @var App\Models\Playlist $playlist */
?>
<article class="panel is-primary">
    <p class="panel-heading">
        Playlists
    </p>
    @foreach ($playlists as $p)
        <a class="panel-block is-block <?= $p->id === $playlist->id ? 'is-bold is-active' : ''; ?>"
           href="<?= route('playlist', [$p]); ?>"
        >
            {{ $p->name }}
        </a>
    @endforeach
</article>





