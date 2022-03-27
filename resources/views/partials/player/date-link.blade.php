<?php
/** @var Carbon\CarbonImmutable $date */
?>
@if ($date->diffInDays() > 21)
    {{ $date->toFormattedDateString() }}
@else
    {{ $date->diffForHumans() }}
@endif
