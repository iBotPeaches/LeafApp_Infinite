<?php
/** @var App\Models\GamePlayer $gamePlayer */
?>
<span
    class="has-tooltip-arrow"
    data-tooltip="Expected Kills ({{ $gamePlayer->expected_kills }})"
>
    {{ $gamePlayer->kills }}
</span>
