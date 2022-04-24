<?php
/** @var App\Models\GamePlayer $gamePlayer */
?>
<span
    class="has-tooltip-arrow"
    data-tooltip="Expected Deaths ({{ $gamePlayer->expected_deaths }})"
>
    @switch (true)
        @case($gamePlayer->deaths < $gamePlayer->expected_deaths)
            <i class="fa fa-level-up-alt"></i>
        @break

        @case($gamePlayer->deaths > $gamePlayer->expected_deaths)
            <i class="fa fa-level-down-alt"></i>
        @break

        @default
            <i class="fa fa-minus"></i>
    @endswitch
</span>
