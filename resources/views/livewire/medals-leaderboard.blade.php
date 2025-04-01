<?php
/** @var App\Models\MedalAnalytic[]|Illuminate\Support\Collection $results */
/** @var App\Models\Season $season */
/** @var App\Enums\Mode $mode */
?>
<div>
    @if ($season->key !== App\Support\Session\SeasonSession::$allSeasonKey && $mode->is(App\Enums\Mode::MATCHMADE_RANKED()))
        <div class="notification is-warning">
            We currently cannot locate filtered data (ie ranked) from a specific season. So this data is misleading as
            it only shows data that was recorded as "current" when it was that previous season.

            <br /><br />
            <span class="is-size-7">
                tldr - don't trust this.
            </span>
        </div>
    @endif
    @if ($results->isEmpty())
        <div class="notification is-warning">
            Oops. No one with this medal in this category yet.
            <br /><br />
            <span class="is-size-7">
                or this Season doesn't have enough data.
            </span>
        </div>
    @else
        <div class="table-container">
            <table class="table is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                <tr>
                    <th>Place</th>
                    <th>Gamertag</th>
                    <th>
                        <img class="image is-32x32" src="{{ $medal->image }}" alt="{{ $medal->name }}">
                    </th>
                    <th>Time Played (hours)</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($results as $result)
                    <tr>
                        <td>
                            @th($result->place)
                        </td>
                        <td>
                            <article class="media">
                                <figure class="media-left">
                                    <p class="image is-32x32">
                                        <img src="{{ $result->player?->emblem_url }}" alt="emblem">
                                    </p>
                                </figure>
                                <div class="media-content">
                                    <div class="content" style="white-space: nowrap">
                                        @include('partials.links.player', ['player' => $result->player])
                                        @if ($result->player?->is_donator)
                                            <span class="tag is-success" data-tooltip="Donated via BuyMeACoffee" style="border-bottom: 0;">
                                                <i class="fas fa-leaf"></i>
                                            </span>
                                        @endif
                                        @if ($result->player?->is_cheater)
                                            <span class="tag is-danger">Banned</span>
                                        @endif
                                        @if ($result->player?->is_botfarmer)
                                            <span class="tag is-info">Farmer</span>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        </td>
                        <td>{{ number_format($result->value) }}</td>
                        <td>
                            <span
                                class="has-tooltip-arrow"
                                data-tooltip="{{ number_format($result->value / max($result->time_played, 1), 2) }} medals per hour."
                            >
                                {{ number_format($result->time_played) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $results->links(data: ['scrollTo' => false]) }}
    @endif
</div>
