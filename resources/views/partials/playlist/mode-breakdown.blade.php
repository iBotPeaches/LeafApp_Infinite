<table class="table is-striped is-narrow is-hoverable is-fullwidth">
    <thead>
    <tr>
        <th>{{ $title }}</th>
        <th>Weight</th>
        @if(isset($changes))
            <th>Change</th>
        @endif
    </tr>
    </thead>
    <tbody>
        @foreach($items as $name => $value)
            <tr>
                <td>{{ $name }}</td>
                <td>{{ number_format($value) }}%</td>
                @if(isset($changes))
                    <td>
                        @if(isset($changes[$name]))
                            @php $change = $changes[$name]; @endphp
                            @if($change['type'] === 'added')
                                <span class="tag is-success is-small">NEW</span>
                            @elseif($change['type'] === 'removed')
                                <span class="tag is-danger is-small">REMOVED</span>
                            @elseif($change['type'] === 'changed')
                                @if($change['difference'] > 0)
                                    <span class="tag is-success is-small">+{{ number_format($change['difference'], 1) }}%</span>
                                @else
                                    <span class="tag is-warning is-small">{{ number_format($change['difference'], 1) }}%</span>
                                @endif
                            @endif
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
        @if(isset($changes))
            @foreach($changes as $name => $change)
                @if($change['type'] === 'removed')
                    <tr>
                        <td>{{ $name }}</td>
                        <td>{{ number_format($change['previous'], 1) }}% (was)</td>
                        <td><span class="tag is-danger is-small">REMOVED</span></td>
                    </tr>
                @endif
            @endforeach
        @endif
    </tbody>
</table>
