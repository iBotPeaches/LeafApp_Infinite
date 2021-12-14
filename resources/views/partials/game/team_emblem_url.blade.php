<?php
/** @var App\Models\GamePlayer $gamePlayer */
?>
@if (empty($gamePlayer->player->emblem_url))
    @if ($gamePlayer->team)
        <img class="is-rounded has-background-dark" src="{{ $gamePlayer->team->emblem_url }}">
    @endif
@else
    <img src="{{ $gamePlayer->player->emblem_url }}">
@endif
