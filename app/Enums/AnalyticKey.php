<?php
declare(strict_types=1);

namespace App\Enums;

enum AnalyticKey: string
{
    case BEST_ACCURACY_SR = 'best_accuracy_sr';
    case BEST_KDA_SR = 'best_kda_sr';
    case BEST_KD_SR = 'best_ka_sr';
    case MOST_BETRAYALS_SR = 'most_betrayals_sr';
    case MOST_KILLS_SR = 'most_kills_sr';
    case MOST_MEDALS_SR = 'most_medals_sr';
    case MOST_TIME_PLAYED_SR = 'most_time_played_sr';

    case MOST_KILLS_RANKED_GAME = 'most_kills_ranked_game';
    case MOST_KILLS_ZERO_DEATHS_GAME = 'most_kills_zero_deaths_game';
    case MOST_KILLS_GAME = 'most_kills_game';
    case MOST_DEATHS_GAME = 'most_deaths_game';
    case MOST_ASSISTS_GAME = 'most_assists_game';
    case MOST_MEDALS_GAME = 'most_medals_game';
    case HIGHEST_SCORE_RANKED_GAME = 'highest_score_ranked_game';
    case LONGEST_MATCHMAKING_GAME = 'longest_matchmaking_game';
}
