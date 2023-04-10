<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\HasHaloDotApi;
use Database\Factories\SeasonFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property string $key
 * @property string $identifier
 * @property string $csr_key
 * @property int $season_id
 * @property int $season_version
 * @property string $name
 * @property string $description
 *
 * @method static SeasonFactory factory(...$parameters)
 */
class Season extends Model implements HasHaloDotApi
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $timestamps = false;

    public static function fromHaloDotApi(array $payload): ?self
    {
        $seasonId = Arr::get($payload, 'id');
        $seasonVersion = Arr::get($payload, 'version');
        $key = $seasonId . '-' . $seasonVersion;

        /** @var Season $season */
        $season = self::query()
            ->where('key', $key)
            ->firstOrNew([
                'key' => $key,
            ]);

        $season->key = $key;
        $season->identifier = Arr::get($payload, 'properties.identifier');
        $season->csr_key = Arr::get($payload, 'properties.csr');
        $season->season_id = $seasonId;
        $season->season_version = $seasonVersion;
        $season->name = Arr::get($payload, 'name');
        $season->description = Arr::get($payload, 'description');

        if ($season->isDirty()) {
            $season->saveOrFail();
        }

        return $season;
    }
}
