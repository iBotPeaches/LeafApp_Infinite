<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $thumbnail_url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $image
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

    public function getImageAttribute(): string
    {
        $filename = Str::slug($this->name).'.jpg';

        if (File::exists(public_path('images/maps/'.$filename))) {
            return asset('images/maps/'.$filename);
        }

        return $this->thumbnail_url;
    }

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
