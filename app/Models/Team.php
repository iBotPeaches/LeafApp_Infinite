<?php

namespace App\Models;

use App\Models\Contracts\HasDotApi;
use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property int $internal_id
 * @property string $name
 * @property string $emblem_url
 *
 * @method static TeamFactory factory(...$parameters)
 */
class Team extends Model implements HasDotApi
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $timestamps = false;

    public static function fromDotApi(array $payload): ?self
    {
        $teamId = Arr::get($payload, 'id');

        /** @var Team $team */
        $team = self::query()
            ->where('internal_id', $teamId)
            ->firstOrNew([
                'internal_id' => $teamId,
            ]);

        $team->name = Arr::get($payload, 'name');
        $team->emblem_url = Arr::get($payload, 'image_urls.icon');

        if ($team->isDirty()) {
            $team->saveOrFail();
        }

        return $team;
    }
}
