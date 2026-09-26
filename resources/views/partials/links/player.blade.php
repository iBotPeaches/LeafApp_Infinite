<?php
    /** @var \App\Models\Player|null $player */
?>
@if ($player)
    <a
        href="{{ route('player', [$player]) }}"
        class="{{ $player->is_cheater ? 'is-cheater' : '' }} {{ $player->is_donator ? 'is-donator' : '' }}"
    >
        {{ $player->gamertag }}
    </a>
@else
    <span class="has-text-grey">Unknown</span>
@endif
