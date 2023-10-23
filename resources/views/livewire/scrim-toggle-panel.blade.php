@auth
    <span>
        | <a class="is-small" wire:click="createScrim">
            create scrim

            @if ($gameCount > 0)
                ({{ $gameCount }})
            @endif
        </a>
    </span>
@elseauth
    <span></span>
@endauth
