<livewire:scrim-players :scrim="$scrim"></livewire:scrim-players>
<div class="notification">
    <a class="is-small" href="{{ route('scrimPlayersCsv', [$scrim]) }}" rel="nofollow">export to csv</a>
</div>
@include('partials.global.under_construction')
