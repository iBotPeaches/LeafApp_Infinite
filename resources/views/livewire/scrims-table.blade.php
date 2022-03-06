<?php
/** @var App\Models\Scrim[] $scrims */
?>
<div>
    <div class="table-container">
        <table class="table is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
            <tr>
                <th>Scrim</th>
                <th>Created By</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($scrims as $scrim)
                <tr>
                    <td>
                        <a href="{{ route('scrim', [$scrim]) }}">
                            this links to scrim
                        </a>
                    </td>
                    <td>
                        @if ($scrim->user->player)
                            <a href="{{ route('player', $scrim->user->player) }}">
                                {{ $scrim->user->player->gamertag }}
                            </a>
                        @else
                            <i>a gamer</i>
                        @endif
                    </td>
                    <td>{{ $scrim->created_at->toFormattedDateString() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $scrims->links() }}
</div>
