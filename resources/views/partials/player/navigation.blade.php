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
        <li class="<?= $type === App\Enums\PlayerTab::MEDALS ? 'is-active' : null; ?>">
            <a href="<?= route('player', [$player, App\Enums\PlayerTab::MEDALS]); ?>">
                <span class="icon"><i class="fa fa-medal"></i></span>
                <span>Medals</span>
            </a>
        </li>
        <li class="<?= $type === App\Enums\PlayerTab::COMPETITIVE ? 'is-active' : null; ?>">
            <a href="<?= route('player', [$player, App\Enums\PlayerTab::COMPETITIVE]); ?>">
                <span class="icon"><i class="fa fa-crosshairs"></i></span>
                <span>Competitive</span>
            </a>
        </li>
        <!--<li class="<?= $type === App\Enums\PlayerTab::MODES ? 'is-active' : null; ?>">
            <a href="<?= route('player', [$player, App\Enums\PlayerTab::MODES]); ?>">
                <span class="icon"><i class="fa fa-chart-simple"></i></span>
                <span>Modes</span>
            </a>
        </li>-->
        <li class="<?= $type === App\Enums\PlayerTab::MATCHES ? 'is-active' : null; ?>">
            <a href="<?= route('player', [$player, App\Enums\PlayerTab::MATCHES]); ?>">
                <span class="icon"><i class="fa fa-history"></i></span>
                <span>Matches</span>
            </a>
        </li>
        <li class="<?= $type === App\Enums\PlayerTab::CUSTOM ? 'is-active' : null; ?>">
            <a href="<?= route('player', [$player, App\Enums\PlayerTab::CUSTOM]); ?>">
                <span class="icon"><i class="fa fa-redo"></i></span>
                <span>Customs</span>
            </a>
        </li>
        @if ($player->faceitPlayers->count() > 0)
            <li class="<?= $type === App\Enums\PlayerTab::LAN ? 'is-active' : null; ?>">
                <a href="<?= route('player', [$player, App\Enums\PlayerTab::LAN]); ?>">
                    <span class="icon"><i class="fa fa-compact-disc"></i></span>
                    <span>LAN</span>
                </a>
            </li>
        @endif
    </ul>
</div>
