<?php
/** @var App\Models\Player $player */
?>
<div>
    @if (!$player->is_cheater)
        <a class="button is-fullwidth is-small is-light is-warning" wire:click="banCheck" wire:confirm="Are you sure you want to check for ban(s)? You can only check 5 profiles a day.">
            Check for ban
        </a>
   @endif
</div>
