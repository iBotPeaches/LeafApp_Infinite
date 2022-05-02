<?php
/** @var App\Models\ServiceRecord|null $serviceRecord */
/** @var App\Models\Player $player */
?>
@if (empty($serviceRecord))
    @if ($player->is_private)
        @include('partials.global.account_private')
    @else
        <div class="notification is-warning">
            <p>
                No record was found for this user for this season. This may be intentional or an outdated profile.
            </p>
            <br /><br />
            <p class="is-size-7">
                If you are still reading this, chances are it's the specific season and not outdated.
            </p>
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
