<?php
/** @var App\Models\Championship $championship */
/** @var string $bracket */
?>
<div class="tabs is-centered">
    <ul>
        <li class="<?= $bracket === App\Enums\Bracket::WINNERS ? 'is-active' : null; ?>">
            <a href="<?= route('championship', [$championship, App\Enums\Bracket::WINNERS]); ?>">
                <span class="icon is-small"><i class="fas fa-trophy" aria-hidden="true"></i></span>
                <span>Bracket</span>
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
