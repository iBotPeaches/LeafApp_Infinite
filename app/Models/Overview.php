<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\OverviewFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\File;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $thumbnail_url
 * @property bool $is_manual
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $image
 * @property-read Collection<OverviewGametype> $gametypes
 * @property-read Collection<OverviewMap> $maps
 * @property-read Collection<OverviewStat> $stats
 *
 * @method static OverviewFactory factory(...$parameters)
 */ class Overview extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getImageAttribute(): string
    {
        $filename = $this->slug.'.jpg';

        if (File::exists(public_path('images/maps/'.$filename))) {
            return asset('images/maps/'.$filename);
        }

        return $this->thumbnail_url;
    }

    /**
     * @return HasMany<OverviewGametype, $this>
     */
    public function gametypes(): HasMany
    {
        return $this->hasMany(OverviewGametype::class);
    }

    /**
     * @return HasMany<OverviewMap, $this>
     */
    public function maps(): HasMany
    {
        return $this->hasMany(OverviewMap::class);
    }

    /**
     * @return HasMany<OverviewStat, $this>
     */
    public function stats(): HasMany
    {
        return $this->hasMany(OverviewStat::class);
    }
}
