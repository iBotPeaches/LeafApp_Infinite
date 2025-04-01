<?php
/** @var App\Models\Player[] $players */
?>
<div>
    <h1 class="title">Banned Halo Infinite Players</h1>
    <h2 class="subtitle">A collection of banned players that Leaf knows about.</h2>
    <div class="table-container">
        <table class="table is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
            <tr>
                <th>Gamertag</th>
                <th>Reason</th>
                <th>Scope</th>
                <th>Expiration</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($players as $player)
                <tr>
                    <td>
                        <article class="media">
                            <figure class="media-left">
                                <p class="image is-32x32">
                                    <img src="{{ $player->emblem_url }}" alt="emblem">
                                </p>
                            </figure>
                            <div class="media-content">
                                <div class="content" style="white-space: nowrap">
                                    @include('partials.links.player', ['player' => $player])
                                    @if ($player->is_donator)
                                        <span class="tag is-success" data-tooltip="Donated via BuyMeACoffee" style="border-bottom: 0;">
                                                <i class="fas fa-leaf"></i>
                                            </span>
                                    @endif
                                    @if ($player->is_cheater)
                                        <span class="tag is-danger">Banned</span>
                                    @endif
                                    @if ($player->is_botfarmer)
                                        <span class="tag is-info">Farmer</span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    </td>
                    <td class="has-tooltip-arrow has-tooltip-text-left-mobile"
                        data-tooltip="{{ $player->latestBan->days_remaining }} days remaining"
                    >
                        {{ $player->latestBan->short_message }}
                    </td>
                    <td>{{ $player->latestBan->scope }}, {{ $player->latestBan->type }}</td>
                    <td>
                        {{ $player->latestBan->ends_at->toFormattedDateString() }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $players->links(data: ['scrollTo' => false]) }}
</div>
