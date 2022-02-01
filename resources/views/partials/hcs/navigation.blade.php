<?php
/** @var App\Models\Championship $championship */
/** @var string $bracket */
?>
@if ($championship->is_ffa)
    @include('partials.hcs.navigation.ffa')
@else
    @include('partials.hcs.navigation.4v4')
@endif
