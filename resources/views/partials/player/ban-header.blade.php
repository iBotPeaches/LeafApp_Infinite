<?php
    /** @var App\Models\Player $player */
?>
@if ($player->bans)
    @foreach ($player->bans as $ban)
        <div class="notification is-danger">
            {{ $ban->message }}
            <br /><br />
            <p class="is-size-7">
                Days remaining: {{ number_format($ban->ends_at->diffInDays(absolute: true)) }}, for '{{ $ban->type }}', on scope: '{{ $ban->scope }}'
            </p>
        </div>
    @endforeach
@endif
