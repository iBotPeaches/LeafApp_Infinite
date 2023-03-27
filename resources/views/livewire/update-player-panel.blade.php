<article class="message is-small {{ $color }}">
    <div class="message-header">
        <p>Want an update?</p>
    </div>
    <div class="message-body">
        @if (isset($button))
            <button
                class="button is-success is-outlined is-fullwidth"
                wire:click="processUpdate"
                wire:loading.remove
            >
                Request Stat Update
            </button>
        @else
            {{ $message }}
        @endif
        <span wire:loading>
            Checking for stats...
        </span>
    </div>
</article>
