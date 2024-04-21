<?php
/** @var App\Models\ServiceRecord|null $serviceRecord */
/** @var App\Models\Player $player */
/** @var App\Models\Season $season */
/** @var App\Enums\Mode $mode */
?>
@if (empty($serviceRecord))
    @if ($player->is_private)
        @include('partials.global.account_private')
    @else
        <div>
            <div class="notification is-warning">
                <p>
                    No record was found for this user for this specific season: <strong>{{ $season->name ?? 'n/a' }}</strong>.
                </p>
                <br /><br />
                <p class="is-size-7">
                    If this is not intended - just click "Request Stat Update"
                </p>
            </div>
            @if ($mode->is(\App\Enums\Mode::MATCHMADE_RANKED()))
                <div class="notification">
                    We currently cannot locate filtered data (ie ranked) from a specific season. So if no data is found,
                    but exists - this is a limitation of current tool.
                </div>
            @endif
        </div>
    @endif
@else
    @if ($player->is_private)
        @include('partials.global.account_private')
    @else
        <div>
            @if ($mode->is(\App\Enums\Mode::MATCHMADE_RANKED()) && !$isAllSeasons)
                <div class="notification is-warning">
                    We currently cannot pull filtered data (ie ranked) from a specific season. If you have data it's from an older Leaf that could.
                </div>
            @endif
            @include('partials.player.stats')
            @if ($serviceRecord->medals)
                @include('partials.player.medal-groups')
            @endif
        </div>
    @endif
@endif
