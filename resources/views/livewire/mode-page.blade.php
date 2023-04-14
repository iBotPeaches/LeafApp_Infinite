<div class="columns">
    <div class="column">
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
    </div>
    <div class="column">
        <article class="panel is-danger">
            <p class="panel-heading">
                Worst Game/Map Type
            </p>
            <div class="panel-block is-block">
                <div class="table-container">
                    @include('partials.player.mode-table', ['mode' => $worse, 'title' => 'Lost'])
                </div>
            </div>
        </article>
    </div>
</div>
<div class="notification is-danger">
    If you are viewing this - you tricked the URL. This is disabled because the migration towards a new API has required
    a re-classification of ALL game data to properly know the category (gametype).

    This is 31 million games soooo it'll take a bit to catch up.
</div>
<div class="notification is-light mt-2">
    Tracking Ranked with requiring a minimum of 5 matches for a map/mode to be included.
</div>
