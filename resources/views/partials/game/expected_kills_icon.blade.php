<?php
/** @var App\Models\GamePlayer $gamePlayer */
?>
<span
    class="has-tooltip-arrow"
    data-tooltip="Expected Kills ({{ $gamePlayer->expected_kills }})"
>
    @switch (true)
        @case($gamePlayer->kills > $gamePlayer->expected_kills)
            <i class="fa fa-level-up-alt"></i>
        @break

        @case($gamePlayer->kills < $gamePlayer->expected_kills)
            <i class="fa fa-level-down-alt"></i>
        @break

        @default
            <i class="fa fa-minus"></i>
    @endswitch
</span>
