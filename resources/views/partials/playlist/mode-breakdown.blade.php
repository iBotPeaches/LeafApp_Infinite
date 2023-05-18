<table class="table is-striped is-narrow is-hoverable is-fullwidth">
    <thead>
    <tr>
        <th>{{ $title }}</th>
        <th>Weight</th>
    </tr>
    </thead>
    <tbody>
        @foreach($items as $name => $value)
            <tr>
                <td>{{ $name }}</td>
                <td>{{ number_format($value) }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>
