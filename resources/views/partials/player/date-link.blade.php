<?php
/** @var Carbon\CarbonImmutable $date */
?>
@if ($date->diffInRealHours() > 24)
    {{ $date->toFormattedDateString() }}
@else
    {{ $date->diffForHumans() }}
@endif
