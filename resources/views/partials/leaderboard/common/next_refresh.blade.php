<?php
/** @var Carbon\Carbon $nextDate */
?>
@if ($nextDate)
    <div class="notification is-light mt-2">
        Next refresh: <i>{{ $nextDate->diffForHumans() }}</i>
    </div>
@endif
