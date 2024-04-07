<?php
    /** @var \App\Models\Player $player */
?>
<a
    href="{{ route('player', [$player]) }}"
    class="{{ $player?->is_cheater ? 'is-cheater' : '' }} {{ $player?->is_donator ? 'is-donator' : '' }}"
>
    {{ $player?->gamertag }}
</a>
