<?php
/** @var App\Models\ServiceRecord|null $serviceRecord */
/** @var App\Models\Player $player */
?>
@if (empty($serviceRecord))
    @if ($player->is_private)
        @include('partials.global.account_private')
    @else
        <div class="notification is-warning">
            No Service Record yet! Hold tight!
        </div>
    @endif
@else
    @if ($player->is_private)
        @include('partials.global.account_private')
    @else
        <div>
            @include('partials.player.stats')
            @if ($serviceRecord->medals)
                @include('partials.player.medal-groups')
            @endif
        </div>
    @endif
@endif
