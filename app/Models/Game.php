<?php

namespace App\Models;

use App\Enums\Experience;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $uuid
 * @property int $category_id
 * @property int $map_id
 * @property boolean $is_ffa
 * @property boolean $is_scored
 * @property Experience $experience
 * @property Carbon $occurred_at
 * @property int $duration_seconds
 * @property-read Category $category
 * @property-read Map $map
 */
class Game extends Model
{
    use HasFactory;

    public $casts = [
        'experience' => Experience::class
    ];

    public function category(): HasOne
    {
        return $this->hasOne(Category::class);
    }

    public function map(): HasOne
    {
        return $this->hasOne(Map::class);
    }
}
