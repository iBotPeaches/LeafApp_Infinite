<?php
    /** @var bool $hasLastChange */
    /** @var \Illuminate\Support\Collection $comparisons */
    $hasComparisons = isset($hasLastChange) && $hasLastChange && isset($comparisons);
?>
<table class="table is-striped is-narrow is-hoverable is-fullwidth">
    <thead>
    <tr>
        <th>{{ $title }}</th>
        <th>Weight</th>
        @if($hasComparisons)
            <th>Change</th>
        @endif
    </tr>
    </thead>
    <tbody>
        @foreach($items as $name => $value)
            @php
                $comparison = $hasComparisons ? $comparisons->get($name) : null;
                $status = $comparison ? $comparison['status'] : 'unchanged';
                $weightChange = $comparison ? $comparison['weightChange'] : null;
                $rowClass = '';

                if ($status === 'new') {
                    $rowClass = 'has-background-success-light';
                } elseif ($status === 'changed') {
                    $rowClass = 'has-background-warning-light';
                }
            @endphp
            <tr class="{{ $rowClass }}">
                <td>{{ $name }}</td>
                <td>{{ number_format($value) }}%</td>
                @if($hasComparisons)
                    <td>
                        @if($status === 'new')
                            <span class="tag is-success">New</span>
                        @elseif($status === 'changed' && $weightChange !== null)
                            <span class="tag {{ $weightChange > 0 ? 'is-success' : 'is-danger' }}">
                                {{ $weightChange > 0 ? '+' : '' }}{{ number_format($weightChange, 2) }}%
                            </span>
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
        @if($hasComparisons)
            @foreach($comparisons as $name => $comparison)
                @if($comparison['status'] === 'removed' && !isset($items[$name]))
                    <tr class="has-background-danger-light">
                        <td>{{ $name }}</td>
                        <td>0%</td>
                        <td><span class="tag is-danger">Removed</span></td>
                    </tr>
                @endif
            @endforeach
        @endif
    </tbody>
</table>
