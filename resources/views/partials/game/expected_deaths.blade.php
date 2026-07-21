<?php
/** @var App\Models\GamePlayer $gamePlayer */
?>
<span
    class="has-tooltip-arrow"
    data-tooltip="Expected Deaths ({{ $gamePlayer->expected_deaths }})"
>
    {{ $gamePlayer->deaths }}
</span>
