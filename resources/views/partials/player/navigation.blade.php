<?php
/** @var App\Models\Player $player */
/** @var string $type */
?>
<div class="tabs is-fullwidth is-centered is-boxed">
    <ul>
        <li class="<?= $type === App\Enums\PlayerTab::OVERVIEW ? 'is-active' : null; ?>">
            <a href="<?= route('player', [$player, App\Enums\PlayerTab::OVERVIEW]); ?>">
                <span class="icon"><i class="fa fa-briefcase"></i></span>
                <span>Overview</span>
            </a>
        </li>
        <li class="<?= $type === App\Enums\PlayerTab::COMPETITIVE ? 'is-active' : null; ?>">
            <a href="<?= route('player', [$player, App\Enums\PlayerTab::COMPETITIVE]); ?>">
                <span class="icon"><i class="fa fa-crosshairs"></i></span>
                <span>Competitive</span>
            </a>
        </li>
        <li class="<?= $type === App\Enums\PlayerTab::MATCHES ? 'is-active' : null; ?>">
            <a href="<?= route('player', [$player, App\Enums\PlayerTab::MATCHES]); ?>">
                <span class="icon"><i class="fa fa-history"></i></span>
                <span>Matches</span>
            </a>
        </li>
    </ul>
</div>
