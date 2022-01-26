<?php
declare(strict_types = 1);

namespace App\Models\Pivots;

use App\Models\Contracts\HasFaceItApi;
use App\Models\Player;
use App\Models\MatchupTeam;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property int $matchup_team_id
 * @property int|null $player_id
 * @property string $faceit_id
 * @property string $faceit_name
 * @property-read MatchupTeam $matchupTeam
 * @property-read Player|null $player
 */
class MatchupPlayer extends Pivot implements HasFaceItApi
{
    public $guarded = [
        'id',
        'player_id',
        'matchup_team_id',
    ];

    public $timestamps = false;

    public static function fromFaceItApi(array $payload): ?self
    {
        $playerId = Arr::get($payload, 'player_id');

        /** @var MatchupTeam $team */
        $team = Arr::get($payload, '_leaf.team');

        /** @var MatchupPlayer $teamPlayer */
        $teamPlayer = self::query()
            ->where('matchup_team_id', $team->id)
            ->where('faceit_id', $playerId)
            ->firstOrNew([
                'faceit_id' => $playerId
            ]);

        $teamPlayer->matchupTeam()->associate($team);
        $teamPlayer->faceit_name = Arr::get($payload, 'game_player_id');

        if ($teamPlayer->isDirty()) {
            $teamPlayer->saveOrFail();
        }

        return $teamPlayer;
    }

    public function matchupTeam(): BelongsTo
    {
        return $this->belongsTo(MatchupTeam::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
