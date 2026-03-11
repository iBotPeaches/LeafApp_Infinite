<table class="table is-striped is-narrow is-hoverable is-fullwidth">
    <thead>
    <tr>
        <th>{{ $title }}</th>
        <th>Weight</th>
        @if (!empty($diffs))
            <th>Change</th>
        @endif
    </tr>
    </thead>
    <tbody>
        @foreach($items as $name => $value)
            <tr>
                <td>{{ $name }}</td>
                <td>{{ number_format($value) }}%</td>
                @if (!empty($diffs))
                    <td>
                        @if (isset($diffs[$name]))
                            @if ($diffs[$name]['type'] === 'added')
                                <span class="tag is-success is-light">Added</span>
                            @elseif ($diffs[$name]['type'] === 'changed')
                                @if ($diffs[$name]['diff'] > 0)
                                    <span class="tag is-success is-light">+{{ number_format($diffs[$name]['diff'], 1) }}%</span>
                                @else
                                    <span class="tag is-danger is-light">{{ number_format($diffs[$name]['diff'], 1) }}%</span>
                                @endif
                            @endif
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
        @if (!empty($diffs))
            @foreach($diffs as $name => $diff)
                @if ($diff['type'] === 'removed')
                    <tr>
                        <td class="has-text-grey-light">{{ $name }}</td>
                        <td class="has-text-grey-light">-</td>
                        <td><span class="tag is-danger is-light">Removed</span></td>
                    </tr>
                @endif
            @endforeach
        @endif
    </tbody>
</table>
