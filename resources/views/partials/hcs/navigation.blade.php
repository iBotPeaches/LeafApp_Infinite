<?php
/** @var App\Models\Championship $championship */
/** @var string $bracket */
?>
@if ($championship->type->isFfa())
    @include('partials.hcs.navigation.ffa')
@elseif ($championship->type->isPoolPlay())
    @include('partials.hcs.navigation.pool_play')
@elseif ($championship->type->isSwiss())
    @include('partials.hcs.navigation.swiss')
@else
    @include('partials.hcs.navigation.4v4')
@endif
