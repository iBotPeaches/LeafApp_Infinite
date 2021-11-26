<?php
/** @var App\Models\ServiceRecord|null $serviceRecord */
?>
@if (empty($serviceRecord))
    <div class="notification is-warning">
        No Service Record yet! Hold tight!
    </div>
@else
    @include('partials.player.stats')
@endif
