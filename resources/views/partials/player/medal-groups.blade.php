<div class="divider is-hidden-mobile">Medals</div>
@foreach ($serviceRecord->hydrated_medals->chunk(5) as $medalGroup)
    <div class="tile is-ancestor mb-4 is-hidden-mobile">
        @include('partials.player.medals')
    </div>
@endforeach
