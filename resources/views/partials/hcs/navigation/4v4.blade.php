<?php
/** @var App\Models\Championship $championship */
/** @var string $bracket */
?>
<div class="tabs is-centered">
    <ul>
        <li class="<?= $bracket === App\Enums\Bracket::WINNERS ? 'is-active' : null; ?>">
            <a href="<?= route('championship', [$championship, App\Enums\Bracket::WINNERS]); ?>">
                <span class="icon is-small"><i class="fas fa-trophy" aria-hidden="true"></i></span>
                <span>Winners</span>
            </a>
        </li>
        <li class="<?= $bracket === App\Enums\Bracket::LOSERS ? 'is-active' : null; ?>">
            <a href="<?= route('championship', [$championship, App\Enums\Bracket::LOSERS]); ?>">
                <span class="icon is-small"><i class="fas fa-gamepad" aria-hidden="true"></i></span>
                <span>Losers</span>
            </a>
        </li>
    </ul>
</div>
