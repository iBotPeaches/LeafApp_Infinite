<?php
use Illuminate\Support\Str;
use App\Enums\AnalyticType;

/** @var App\Models\ServiceRecord[]|App\Models\GamePlayer[] $results */
/** @var App\Support\Analytics\AnalyticInterface $analyticClass */
?>
<div>
    @if ($results->isEmpty())
        <div class="notification is-warning">
            Oops. No one with this stat yet - just wait an hour.
        </div>
    @else
        <div class="table-container">
            <table class="table is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                    <tr>
                        <th>Place</th>
                        @if ($analyticClass->type()->isNot(AnalyticType::ONLY_GAME()))
                            <th>Gamertag</th>
                        @endif
                        @if ($analyticClass->type()->isGame())
                            <th>Game</th>
                        @endif
                        <th>{{ Str::title($analyticClass->unit()) }}</th>
                        @if ($analyticClass->type()->isGame())
                            <th>Date</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                @foreach ($results as $result)
                    <tr>
                        <td>
                            @th($loop->iteration)
                        </td>
                        @if ($analyticClass->type()->isNot(AnalyticType::ONLY_GAME()))
                            <td>
                                <article class="media">
                                    <figure class="media-left">
                                        <p class="image is-32x32">
                                            <img src="{{ $result->player?->emblem_url }}" alt="emblem">
                                        </p>
                                    </figure>
                                    <div class="media-content">
                                        <div class="content" style="white-space: nowrap">
                                            <a href="{{ route('player', [$result->player]) }}">
                                                {{ $result->player->gamertag }}
                                            </a>
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
            <div class="notification is-light is-hidden-mobile mt-2">
                export to csv: <a href="#">top 10</a>, <a href="#">top 100</a> or <a href="#">top 1,000</a>.
            </div>
        </div>
    @endif
</div>
