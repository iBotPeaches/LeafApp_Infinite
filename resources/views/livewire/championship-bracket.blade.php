<?php
/** @var App\Models\Championship $championship */
?>
<div>
    <div class="columns">
        <div class="column is-one-third">
            <div class="card">
                <div class="card-content">
                    <p class="title">{{ $championship->name }}</p>
                </div>
            </div>
        </div>
        <div class="column">
            @include('partials.hcs.navigation')
        </div>
    </div>
    <div class="columns">
        <div class="column is-one-fifth">
            @include('partials.hcs.bracket_panel')
        </div>
        <div class="column">
            @include('partials.hcs.bracket_table')
        </div>
    </div>
</div>
