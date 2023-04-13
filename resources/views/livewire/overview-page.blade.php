<?php
/** @var App\Models\ServiceRecord|null $serviceRecord */
/** @var App\Models\Player $player */
/** @var App\Models\Season $season */
?>
@if (empty($serviceRecord))
    @if ($player->is_private)
        @include('partials.global.account_private')
    @else
        <div class="notification is-warning">
            <p>
                No record was found for this user for this specific season: <strong>{{ $season->name ?? 'n/a' }}</strong>.
            </p>
            <br /><br />
            <p class="is-size-7">
                If this is not intended - just click "Request Stat Update"
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
