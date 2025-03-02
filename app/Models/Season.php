<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Mode;
use App\Models\Contracts\HasDotApi;
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
class Season extends Model implements HasDotApi
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $timestamps = false;

    public static function ofSeasonIdentifierOrKey(?string $key = null): ?self
    {
        if (! $key) {
            return null;
        }

        return self::query()
            ->where('identifier', $key)
            ->orWhere('csr_key', $key)
            ->first();
    }

    public static function latestOfSeason(int $seasonNumber): ?self
    {
        return self::query()
            ->where('season_id', $seasonNumber)
            ->orderByDesc('season_version')
            ->first();
    }

    public static function fromDotApi(array $payload): ?self
    {
        $seasonId = Arr::get($payload, 'id');
        $seasonVersion = Arr::get($payload, 'version');
        $key = $seasonId.'-'.$seasonVersion;

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
            $season->save();
        }

        return $season;
    }

    public function getAvailableFilters(): array
    {
        return [
            Mode::MATCHMADE_PVP(),
        ];
    }
}
