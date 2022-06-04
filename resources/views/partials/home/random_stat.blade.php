<?php

use App\Enums\AnalyticType;

/** @var App\Support\Analytics\AnalyticInterface $analytic */
?>
@if ($analytic->type()->is(AnalyticType::PLAYER()))
    @include('partials.home.random_stat_player', ['record' => $analytic->result()])
@elseif ($analytic->type()->is(AnalyticType::GAME()))
    @include('partials.home.random_stat_game', ['gamePlayer' => $analytic->result()])
@endif
