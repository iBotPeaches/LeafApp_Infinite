<?php
declare(strict_types = 1);

namespace App\Models;

use App\Models\Contracts\HasFaceItApi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property string $faceit_id
 * @property int $championship_id
 * @property int $round
 * @property int $group
 * @property int $best_of
 * @property Carbon $started_at
 * @property Carbon $ended_at
 * @property-read Championship $championship
 */
class Matchup extends Model implements HasFaceItApi
{
    use HasFactory;

    public $guarded = [
        'id',
        'championship_id',
    ];

    public $timestamps = false;

    public function setStartedAtAttribute(string $value): void
    {
        $this->attributes['started_at'] = Carbon::createFromTimestampMsUTC($value);
    }

    public function setEndedAtAttribute(string $value): void
    {
        $this->attributes['ended_at'] = Carbon::createFromTimestampMsUTC($value);
    }

    public static function fromFaceItApi(array $payload): ?self
    {
        $matchId = Arr::get($payload, 'match_id');

        /** @var Championship $championship */
        $championship = Arr::get($payload, '_leaf.championship');

        /** @var Matchup $matchup */
        $matchup = self::query()
            ->where('faceit_id', $matchId)
            ->firstOrNew([
                'faceit_id' => $matchId
            ]);

        $matchup->championship()->associate($championship);
        $matchup->round = Arr::get($payload, 'round');
        $matchup->group = Arr::get($payload, 'group');
        $matchup->best_of = Arr::get($payload, 'best_of');
        $matchup->started_at = Arr::get($payload, 'started_at');
        $matchup->ended_at = Arr::get($payload, 'finished_at');

        if ($matchup->isDirty()) {
            $matchup->saveOrFail();
        }

        return $matchup;
    }

    public function championship(): BelongsTo
    {
        return $this->belongsTo(Championship::class);
    }
}
