<?php
/** @var App\Models\Scrim $scrim */
/** @var string $type */
?>
<div class="tabs is-fullwidth is-centered is-toggle">
    <ul>
        <li class="<?= $type === App\Enums\ScrimTab::OVERVIEW ? 'is-active' : null; ?>">
            <a href="<?= route('player', [$scrim, App\Enums\ScrimTab::OVERVIEW]); ?>">
                <span class="icon"><i class="fa fa-briefcase"></i></span>
                <span>Overview</span>
            </a>
        </li>
    </ul>
</div>
