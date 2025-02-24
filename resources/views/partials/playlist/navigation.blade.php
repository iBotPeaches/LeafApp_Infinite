<?php
/** @var App\Models\Playlist $playlist */
/** @var string $type */
?>
<div class="tabs is-fullwidth is-centered is-boxed">
    <ul>
        <li class="<?= $type === App\Enums\PlaylistTab::OVERVIEW ? 'is-active' : null; ?>">
            <a href="<?= route('playlist', [$playlist, App\Enums\PlaylistTab::OVERVIEW]); ?>">
                <span class="icon"><i class="fa fa-briefcase"></i></span>
                <span>Overview</span>
            </a>
        </li>
        <li class="<?= $type === App\Enums\PlaylistTab::STATS ? 'is-active' : null; ?>">
            <a href="<?= route('playlist', [$playlist, App\Enums\PlaylistTab::STATS]); ?>">
                <span class="icon"><i class="fa fa-chart-simple"></i></span>
                <span>Stats</span>
            </a>
        </li>
    </ul>
</div>
