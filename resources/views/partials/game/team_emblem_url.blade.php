<?php
/** @var App\Models\GamePlayer $gamePlayer */
?>
@if (empty($gamePlayer->player->emblem_url))
    <img class="is-rounded has-background-dark" src="{{ $gamePlayer->team->emblem_url }}">
@else
    <img src="{{ $gamePlayer->player->emblem_url }}">
@endif
