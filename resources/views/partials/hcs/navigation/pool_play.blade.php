<?php
/** @var App\Models\Championship $championship */
/** @var string $bracket */
?>
<div class="tabs is-centered">
    <ul>
        <li class="<?= $bracket === App\Enums\Bracket::POOL_A ? 'is-active' : null; ?>">
            <a href="<?= route('championship', [$championship, App\Enums\Bracket::POOL_A]); ?>">
                <span class="icon is-small"><i class="fas fa-gamepad" aria-hidden="true"></i></span>
                <span>Pool A</span>
            </a>
        </li>
        <li class="<?= $bracket === App\Enums\Bracket::POOL_B ? 'is-active' : null; ?>">
            <a href="<?= route('championship', [$championship, App\Enums\Bracket::POOL_B]); ?>">
                <span class="icon is-small"><i class="fas fa-gamepad" aria-hidden="true"></i></span>
                <span>Pool B</span>
            </a>
        </li>
        <li class="<?= $bracket === App\Enums\Bracket::POOL_C ? 'is-active' : null; ?>">
            <a href="<?= route('championship', [$championship, App\Enums\Bracket::POOL_C]); ?>">
                <span class="icon is-small"><i class="fas fa-gamepad" aria-hidden="true"></i></span>
                <span>Pool C</span>
            </a>
        </li>
        <li class="<?= $bracket === App\Enums\Bracket::POOL_D ? 'is-active' : null; ?>">
            <a href="<?= route('championship', [$championship, App\Enums\Bracket::POOL_D]); ?>">
                <span class="icon is-small"><i class="fas fa-gamepad" aria-hidden="true"></i></span>
                <span>Pool D</span>
            </a>
        </li>
        @if ($championship->description)
            <li class="<?= $bracket === App\Enums\Bracket::RULES ? 'is-active' : null; ?>">
                <a href="<?= route('championship', [$championship, App\Enums\Bracket::RULES]); ?>">
                    <span class="icon is-small"><i class="fas fa-wrench" aria-hidden="true"></i></span>
                    <span>Rules</span>
                </a>
            </li>
        @endif
    </ul>
</div>
