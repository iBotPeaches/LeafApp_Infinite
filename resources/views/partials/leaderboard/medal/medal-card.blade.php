<div class="card mb-2">
    <div class="card-image">
        <figure class="image">
            <img src="{{ $medal->image }}" alt="{{ $medal->name }}">
        </figure>
    </div>
    <div class="card-content">
        <h1 class="title is-4">
            {{ $medal->name }}
        </h1>
        <h2 class="subtitle is-6">
            {{ $medal->description }}
        </h2>
        <span class="tag is-{{ $medal->color }}">
            {{ $medal->difficulty->description }}
        </span>
    </div>
</div>
