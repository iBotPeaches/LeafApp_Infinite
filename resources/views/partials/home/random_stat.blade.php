<?php
/** @var App\Support\Analytics\AnalyticInterface $analyticClass */
/** @var App\Models\Analytic $analytic */
?>
@if ($analyticClass->type()->is(App\Enums\AnalyticType::PLAYER()) && $analytic)
    @include('partials.home.random_stat_player', ['record' => $analytic])
@elseif ($analyticClass->type()->is(App\Enums\AnalyticType::GAME()) && $analytic)
    @include('partials.home.random_stat_game', ['gamePlayer' => $analytic])
@elseif ($analyticClass->type()->is(App\Enums\AnalyticType::ONLY_GAME()) && $analytic)
    @include('partials.home.random_stat_only_game', ['game' => $analytic])
@endif
