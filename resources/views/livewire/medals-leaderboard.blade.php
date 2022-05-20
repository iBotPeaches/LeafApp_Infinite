<?php
/** @var App\Models\ServiceRecord[] $results */
?>
<div>
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
                    <th>Gamertag</th>
                    <th>Count</th>
                    <th>Time Played (hours)</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($results as $result)
                    <tr>
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
                        <td>{{ number_format($result->value) }}</td>
                        <td>{{ $result->time_played }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $results->links() }}
    @endif
</div>
