<?php
/** @var Carbon\Carbon $nextDate */
?>
@if ($nextDate)
    <div class="notification is-light mb-2">
        Next refresh: <i>{{ $nextDate->diffForHumans() }}</i>
    </div>
@endif
