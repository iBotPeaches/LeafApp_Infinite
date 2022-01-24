<?php
declare(strict_types = 1);

namespace App\Models\Pivots;

use App\Enums\Outcome;
use App\Models\Contracts\HasFaceItApi;
use App\Models\Matchup;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property int $team_id
 * @property int|null $player_id
 * @property string $faceit_id
 * @property string $faceit_name
 * @property-read Team $team
 * @property-read Player|null $player
 */
class TeamPlayer extends Pivot implements HasFaceItApi
{
    public $guarded = [
        'id',
        'player_id',
        'team_id',
    ];

    public $timestamps = false;

    public static function fromFaceItApi(array $payload): ?self
    {
        $playerId = Arr::get($payload, 'player_id');

        /** @var Team $team */
        $team = Arr::get($payload, '_leaf.team');

        /** @var TeamPlayer $teamPlayer */
        $teamPlayer = self::query()
            ->where('team_id', $team->id)
            ->where('faceit_id', $playerId)
            ->firstOrNew([
                'faceit_id' => $playerId
            ]);

        $teamPlayer->team()->associate($team);
        $teamPlayer->faceit_name = Arr::get($payload, 'game_player_id');

        if ($teamPlayer->isDirty()) {
            $teamPlayer->saveOrFail();
        }

        return $teamPlayer;
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
