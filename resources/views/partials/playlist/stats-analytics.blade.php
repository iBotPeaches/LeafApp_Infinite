<?php
/** @var Illuminate\Database\Eloquent\Collection<int, App\Models\PlaylistAnalytic> $analyticGroup */
use App\Enums\AnalyticType;
?>
<div>
    @if ($analytics->isEmpty())
        <div class="notification is-warning">
            No analytics found for this playlist.
        </div>
    @else
        @foreach ($analytics as $analyticGroup)
            @php($analyticClass = $analyticGroup->first()->stat)
            <article class="panel is-info">
                <p class="panel-heading">
                    {{ $analyticGroup->first()->stat->title() }}
                </p>
                <div class="table-container">
                    <table class="table is-striped is-narrow is-hoverable is-fullwidth">
                        <thead>
                        <tr>
                            <th>Place</th>
                            @if ($analyticClass->type()->notIn([AnalyticType::ONLY_GAME(), AnalyticType::OVERVIEW_STAT()]))
                                <th>Gamertag</th>
                            @endif
                            @if ($analyticClass->type()->isGame())
                                <th>Game</th>
                            @endif
                            @if ($analyticClass->type()->isOverviewStat())
                                <th>Map</th>
                            @endif
                            <th>{{ Str::title($analyticClass->unit()) }}</th>
                            @if ($analyticClass->type()->isGame())
                                <th>Date</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($analyticGroup as $result)
                            <tr>
                                <td>
                                    @th($result->place)
                                </td>
                                @if ($analyticClass->type()->notIn([AnalyticType::ONLY_GAME(), AnalyticType::OVERVIEW_STAT()]))
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
                                @endif
                                @if ($analyticClass->type()->isGame())
                                    <td>
                                        <a href="{{ route('game', [$result->game]) }}">
                                            {{ $result->game->name }}
                                        </a>
                                    </td>
                                @endif
                                <td>{{ $analyticClass->displayProperty($result) }}</td>
                                @if ($analyticClass->type()->isGame())
                                    <td>
                                        @include('partials.player.date-link', ['date' => $result->game->occurred_at])
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </article>
        @endforeach
    @endif
    <br />
</div>
