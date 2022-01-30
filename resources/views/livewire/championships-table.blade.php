<?php
/** @var App\Models\Championship[] $championships */
?>
<div>
    <table class="table is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
        <tr>
            <th>Tournament</th>
            <th>Region</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($championships as $championship)
            <tr>
                <td>
                    <a href="{{ route('championship', [$championship]) }}">
                        {{ $championship->name }}
                    </a>
                </td>
                <td>{{ $championship->region->description }}</td>
                <td>{{ $championship->started_at->toDateString() }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $championships->links() }}
</div>
