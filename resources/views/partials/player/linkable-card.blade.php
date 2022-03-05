@if ($user->player && $player->id === $user->player->id)
    <a class="button is-fullwidth is-small is-danger" href="{{ route('playerUnlink', $player) }}" onclick="event.preventDefault(); document.getElementById('unlink-form').submit();">
        This is not me.
    </a>

    <form id="unlink-form" action="{{ route('playerUnlink', $player) }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
@endif
@if (empty($user->player))
    <a class="button is-fullwidth is-small is-primary" href="{{ route('playerLink', $player) }}" onclick="event.preventDefault(); document.getElementById('link-form').submit();">
        This is me.
    </a>

    <form id="link-form" action="{{ route('playerLink', $player) }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
@endif
