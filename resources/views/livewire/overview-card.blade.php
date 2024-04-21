<?php
/** @var App\Models\Overview $overview */
?>
<div>
    <div class="card">
        <div class="card-image">
            <figure class="image is-4by3">
                <img src="{{ $overview->image }}" alt="Map image">
            </figure>
        </div>
        <div class="card-content">
            <div class="media">
                <div class="media-content">
                    <p class="title is-4">
                        {{ $overview->name }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    @include('partials.overview.next-refresh')
</div>
