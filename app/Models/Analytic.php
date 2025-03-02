<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AnalyticKey;
use App\Support\Analytics\AnalyticInterface;
use App\Support\Analytics\Stats\BestAccuracyServiceRecord;
use App\Support\Analytics\Stats\BestKDAServiceRecord;
use App\Support\Analytics\Stats\BestKDServiceRecord;
use App\Support\Analytics\Stats\HighestScoreInRankedGame;
use App\Support\Analytics\Stats\HighestScoreInUnrankedGame;
use App\Support\Analytics\Stats\LongestMatchmakingGame;
use App\Support\Analytics\Stats\MostAssistsInGame;
use App\Support\Analytics\Stats\MostBetrayalsServiceRecord;
use App\Support\Analytics\Stats\MostCalloutAssistsServiceRecord;
use App\Support\Analytics\Stats\MostDeathsInGame;
use App\Support\Analytics\Stats\MostGamesPlayedServiceRecord;
use App\Support\Analytics\Stats\MostKillsInGame;
use App\Support\Analytics\Stats\MostKillsInRankedGame;
use App\Support\Analytics\Stats\MostKillsServiceRecord;
use App\Support\Analytics\Stats\MostKillsWithZeroDeathsGame;
use App\Support\Analytics\Stats\MostMedalsInGame;
use App\Support\Analytics\Stats\MostMedalsServiceRecord;
use App\Support\Analytics\Stats\MostPerfectsInRankedGame;
use App\Support\Analytics\Stats\MostQuitMap;
use App\Support\Analytics\Stats\MostScoreServiceRecord;
use App\Support\Analytics\Stats\MostTimePlayedServiceRecord;
use Carbon\Carbon;
use Database\Factories\AnalyticFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use UnexpectedValueException;

/**
 * @property int $id
 * @property string $key
 * @property ?int $place
 * @property ?int $game_id
 * @property ?int $player_id
 * @property float $value
 * @property string|null $label
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read ?Game $game
 * @property-read ?Player $player
 * @property-read AnalyticInterface $stat
 *
 * @method static AnalyticFactory factory(...$parameters)
 */
class Analytic extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public function getStatAttribute(): AnalyticInterface
    {
        return self::getStatFromEnum(AnalyticKey::tryFrom($this->key));
    }

    public static function getStatFromEnum(?AnalyticKey $key): AnalyticInterface
    {
        return match ($key) {
            AnalyticKey::MOST_TIME_PLAYED_SR => new MostTimePlayedServiceRecord,
            AnalyticKey::MOST_KILLS_SR => new MostKillsServiceRecord,
            AnalyticKey::MOST_KILLS_RANKED_GAME => new MostKillsInRankedGame,
            AnalyticKey::MOST_KILLS_ZERO_DEATHS_GAME => new MostKillsWithZeroDeathsGame,
            AnalyticKey::MOST_CALLOUT_ASSISTS_SR => new MostCalloutAssistsServiceRecord,
            AnalyticKey::MOST_ASSISTS_GAME => new MostAssistsInGame,
            AnalyticKey::MOST_DEATHS_GAME => new MostDeathsInGame,
            AnalyticKey::MOST_KILLS_GAME => new MostKillsInGame,
            AnalyticKey::MOST_MEDALS_GAME => new MostMedalsInGame,
            AnalyticKey::MOST_PERFECTS_RANKED_GAME => new MostPerfectsInRankedGame,
            AnalyticKey::HIGHEST_SCORE_RANKED_GAME => new HighestScoreInRankedGame,
            AnalyticKey::HIGHEST_SCORE_UNRANKED_GAME => new HighestScoreInUnrankedGame,
            AnalyticKey::LONGEST_MATCHMAKING_GAME => new LongestMatchmakingGame,
            AnalyticKey::MOST_BETRAYALS_SR => new MostBetrayalsServiceRecord,
            AnalyticKey::MOST_MEDALS_SR => new MostMedalsServiceRecord,
            AnalyticKey::BEST_ACCURACY_SR => new BestAccuracyServiceRecord,
            AnalyticKey::BEST_KD_SR => new BestKDServiceRecord,
            AnalyticKey::BEST_KDA_SR => new BestKDAServiceRecord,
            AnalyticKey::MOST_QUIT_MAP => new MostQuitMap,
            AnalyticKey::MOST_SCORE_SR => new MostScoreServiceRecord,
            AnalyticKey::MOST_GAMES_PLAYED_SR => new MostGamesPlayedServiceRecord,
            default => throw new UnexpectedValueException('Unknown value in getStatFromEnum')
        };
    }

    public static function purgeKey(string $key): void
    {
        self::query()
            ->where('key', $key)
            ->delete();
    }

    /**
     * @return BelongsTo<Game, $this>
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * @return BelongsTo<Player, $this>
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
