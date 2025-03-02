<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\OverviewMapFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $overview_id
 * @property int $map_id
 * @property Carbon $released_at
 * @property-read Overview $overview
 *
 * @method static OverviewMapFactory factory(...$parameters)
 */
class OverviewMap extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $casts = [
        'released_at' => 'datetime',
    ];

    public $timestamps = false;

    /**
     * @return BelongsTo<Overview, $this>
     */
    public function overview(): BelongsTo
    {
        return $this->belongsTo(Overview::class);
    }
}
