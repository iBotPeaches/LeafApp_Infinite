<?php
/** @var App\Models\Player[] $lastUpdated */
?>
<section class="card">
    <header class="card-header">
        <p class="card-header-title">
            Recently Updated
        </p>
    </header>
    <div class="card-content">
        <table class="table table-bordered is-fullwidth">
            <thead>
            <tr>
                <th>Gamertag</th>
                <th>Tag</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($lastUpdated as $player)
                <tr>
                    <td>
                        <article class="media">
                            <figure class="media-left">
                                <p class="image is-32x32">
                                    <img src="{{ $player->emblem_url }}" alt="{{ $player->gamertag }} Emblem"/>
                                </p>
                            </figure>
                            <div class="media-content">
                                <div class="content">
                                    <a href="{{ route('player', [$player]) }}">
                                        {{ $player->gamertag }}
                                    </a>
                                </div>
                            </div>
                        </article>
                    </td>
                    <td>{{ $player->service_tag }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</section>
