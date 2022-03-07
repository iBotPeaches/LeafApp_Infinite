<?php
/** @var App\Models\Scrim $scrim */
/** @var string $type */
?>
<div class="tabs is-fullwidth is-centered is-toggle">
    <ul>
        <li class="<?= $type === App\Enums\ScrimTab::OVERVIEW ? 'is-active' : null; ?>">
            <a href="<?= route('scrim', [$scrim, App\Enums\ScrimTab::OVERVIEW]); ?>">
                <span class="icon"><i class="fa fa-briefcase"></i></span>
                <span>Overview</span>
            </a>
        </li>
        <li class="<?= $type === App\Enums\ScrimTab::MATCHES ? 'is-active' : null; ?>">
            <a href="<?= route('scrim', [$scrim, App\Enums\ScrimTab::MATCHES]); ?>">
                <span class="icon"><i class="fa fa-history"></i></span>
                <span>Matches</span>
            </a>
        </li>
        <li class="<?= $type === App\Enums\ScrimTab::PLAYERS ? 'is-active' : null; ?>">
            <a href="<?= route('scrim', [$scrim, App\Enums\ScrimTab::PLAYERS]); ?>">
                <span class="icon"><i class="fa fa-users"></i></span>
                <span>Players</span>
            </a>
        </li>
    </ul>
</div>
