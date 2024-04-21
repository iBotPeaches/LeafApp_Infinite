<?php
/** @var App\Models\Overview $overview */
/** @var string $tab */
?>
<div class="tabs is-fullwidth is-centered is-boxed">
    <ul>
        <li class="<?= $tab === App\Enums\OverviewTab::OVERVIEW ? 'is-active' : null; ?>">
            <a href="<?= route('overview', [$overview, App\Enums\OverviewTab::OVERVIEW]); ?>">
                <span class="icon"><i class="fa fa-earth"></i></span>
                <span>Overview</span>
            </a>
        </li>
        <li class="<?= $tab === App\Enums\OverviewTab::MATCHES ? 'is-active' : null; ?>">
            <a href="<?= route('overview', [$overview, App\Enums\OverviewTab::MATCHES]); ?>">
                <span class="icon"><i class="fa fa-history"></i></span>
                <span>Matches</span>
            </a>
        </li>
    </ul>
</div>
