<article class="message is-small {{ $color }} mb-2">
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
            <small wire:loading.remove>
                last updated:
                <time class="local-date" datetime="{{ $player->updated_at->toIso8601ZuluString() }}">
                    {{ $player->updated_at->toDayDateTimeString() }} (UTC)
                </time>
            </small>
        @else
            {{ $message }}
        @endif
        <span wire:loading>
            Checking for stats...
        </span>
    </div>
</article>
