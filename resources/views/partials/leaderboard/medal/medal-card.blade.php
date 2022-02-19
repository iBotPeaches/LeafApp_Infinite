<div class="card mb-2">
    <div class="card-image">
        <figure class="image">
            <img src="{{ $medal->image }}">
        </figure>
    </div>
    <div class="card-content">
        <p class="title is-4">
            {{ $medal->name }}
        </p>
        <p class="subtitle is-6">
            {{ $medal->description }}
        </p>
        <span class="tag is-{{ $medal->color }}">
            {{ $medal->type->description }}
        </span>
    </div>
</div>
