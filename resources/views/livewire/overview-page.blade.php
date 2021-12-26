<?php
/** @var App\Models\ServiceRecord|null $serviceRecord */
/** @var App\Models\Player $player */
?>
@if (empty($serviceRecord))
    <div class="notification is-warning">
        No Service Record yet! Hold tight!
    </div>
@else
    @if ($player->is_private)
        <article class="message is-warning">
            <div class="message-header">
                <p>Warning - Account Private</p>
            </div>
            <div class="message-body content">
                This account is not allowing API access to information.
                If this is your account and you want this to work. Please try:
                <ul>
                    <li>Halo Infinite Settings</li>
                    <li>Accessibility Tab (scroll to bottom)</li>
                    <li>Share Matchmade Games</li>
                </ul>
            </div>
        </article>
    @else
        <div>
            @include('partials.player.stats')
            @if ($serviceRecord->medals)
                @include('partials.player.medal-groups')
            @endif
        </div>
    @endif
@endif
