<?php
/** @var Carbon\CarbonImmutable $date */
?>
@if ($date->diffInHours(absolute: true) > 24)
    {{ $date->toFormattedDateString() }}
@else
    {{ $date->diffForHumans() }}
@endif
