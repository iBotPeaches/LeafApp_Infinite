<div>
    <article class="panel is-primary">
        <p class="panel-heading">
            Best Game/Map Type
        </p>
        <div class="panel-block is-block">
            <div class="table-container">
                @include('partials.player.mode-table', ['mode' => $best, 'title' => 'Won'])
            </div>
        </div>
    </article>
    <article class="panel is-danger">
        <p class="panel-heading">
            Worse Game/Map Type
        </p>
        <div class="panel-block is-block">
            <div class="table-container">
                @include('partials.player.mode-table', ['mode' => $worse, 'title' => 'Lost'])
            </div>
        </div>
    </article>
</div>
