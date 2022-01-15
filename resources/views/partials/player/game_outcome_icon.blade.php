<?php
/** @var App\Models\Game $game */
?>
<span
    class="has-tooltip-arrow"
    data-tooltip="CSR: {{ $game->personal->pre_csr }} ({{ $game->personal->csr_change }})"
>
    @switch (true)
        @case($game->personal->csr_change_raw > 0)
            <i class="fa fa-level-up-alt"></i>
        @break

        @case($game->personal->csr_change_raw < 0)
            <i class="fa fa-level-down-alt"></i>
        @break

        @default
            <i class="fa fa-minus"></i>
    @endswitch
</span>
