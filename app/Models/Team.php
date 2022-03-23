<?php

namespace App\Models;

use App\Models\Contracts\HasHaloDotApi;
use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $emblem_url
 * @method static TeamFactory factory(...$parameters)
 */
class Team extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id'
    ];

    public $timestamps = false;

    public static function fromHaloDotApi(array $payload): ?self
    {
        $teamId = Arr::get($payload, 'id');

        /** @var Team $team */
        $team = self::query()
            ->where('internal_id', $teamId)
            ->firstOrNew([
                'internal_id' => $teamId
            ]);

        $team->name = Arr::get($payload, 'name');
        $team->emblem_url = Arr::get($payload, 'emblem_url');

        if ($team->isDirty()) {
            $team->saveOrFail();
        }

        return $team;
    }
}
