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
                        <th>Gamertag</th>
                        @if ($analyticClass->type()->is(AnalyticType::GAME()))
                            <th>Game</th>
                        @endif
                        <th>{{ Str::title($analyticClass->unit()) }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($results as $result)
                    <tr>
                        <td>
                            @th($loop->iteration)
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
                                        <a href="{{ route('player', [$result->player]) }}">
                                            {{ $result->player->gamertag }}
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </td>
                        @if ($analyticClass->type()->is(AnalyticType::GAME()))
                            <td>
                                <a href="{{ route('game', [$result->game]) }}">
                                    {{ $result->game->name }}
                                </a>
                            </td>
                        @endif
                        <td>{{ $analyticClass->displayProperty($result) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $results->links() }}
    @endif
</div>
