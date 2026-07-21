<?php
/** @var App\Models\Medal[] $medals */
?>
<div>
    <div class="table-container">
        <table class="table is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
            <tr>
                <th>Medal</th>
                <th>Type</th>
                <th>Difficulty</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($medals as $medal)
                <tr>
                    <td class="grid">
                        <article class="cell is-flex">
                            <figure class="media-left">
                                <p class="image is-48x48">
                                    <img src="{{ $medal->image }}" alt="{{ $medal->name }}" />
                                </p>
                            </figure>
                            <div class="media-content">
                                <div class="content">
                                    <p>
                                        <a href="{{ route('medalLeaderboard', [$medal]) }}">
                                            <strong style="white-space: nowrap">
                                                {{ $medal->name }}
                                            </strong>
                                        </a>
                                        <br>
                                        {{ $medal->description }}
                                    </p>
                                </div>
                            </div>
                        </article>
                    </td>
                    <td>{{ $medal->type->description }}</td>
                    <td>
                        <span class="tag is-{{ $medal->color }}">
                            {{ $medal->difficulty->description }}
                        </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $medals->links() }}
</div>
