<?php
/** @var Carbon\Carbon $nextDate */
?>
@if ($nextDate)
    <div class="notification is-light mt-2">
        @if ($nextDate->isPast())
            <i>Refresh Pending</i>
        @else
            Next refresh: <i>{{ $nextDate->diffForHumans() }}</i>
        @endif
    </div>
@endif
