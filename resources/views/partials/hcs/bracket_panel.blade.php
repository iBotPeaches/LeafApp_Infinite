<?php
/** @var App\Models\Championship $championship */
/** @var string $bracket */
/** @var int $round */
?>
<article class="panel is-primary">
    <p class="panel-heading">
        Rounds
    </p>
    @foreach ($rounds as $roundNumber => $teams)
        <a class="panel-block is-block"
           href="<?= route('championship', [$championship, $bracket, $roundNumber]); ?>"
        >
            Round {{ $roundNumber }}
            <span class="is-pulled-right">
                <span class="tag <?= $round == $roundNumber ? 'is-success' : 'is-light'; ?>">{{ $teams }} teams</span>
            </span>
        </a>
    @endforeach
</article>
