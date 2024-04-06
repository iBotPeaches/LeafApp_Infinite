<?php
    /** @var \App\Models\Player $player */
?>
<a href="{{ route('player', [$player]) }}" class="{{ $player?->is_cheater ? 'is-cheater' : '' }}">
    {{ $player?->gamertag }}
</a>
