<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $image
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<OverviewGametype> $gametypes
 * @property-read Collection<OverviewMap> $maps
 * @property-read Collection<OverviewStat> $stats
 */
class Overview extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public function gametypes(): HasMany
    {
        return $this->hasMany(OverviewGametype::class);
    }

    public function maps(): HasMany
    {
        return $this->hasMany(OverviewMap::class);
    }

    public function stats(): HasMany
    {
        return $this->hasMany(OverviewStat::class);
    }
}
