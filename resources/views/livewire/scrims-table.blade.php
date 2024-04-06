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
                            Scrim {{ $scrim->id }}
                        </a>
                    </td>
                    <td>
                        @if ($scrim->user->player)
                            @include('partials.links.player', ['player' => $scrim->user->player])
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
    {{ $scrims->links(data: ['scrollTo' => false]) }}
</div>
