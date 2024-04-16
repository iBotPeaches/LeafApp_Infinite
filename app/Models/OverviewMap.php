<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $overview_id
 * @property int $map_id
 * @property string $released_at
 * @property-read Overview $overview
 */
class OverviewMap extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $timestamps = false;

    public function overview(): BelongsTo
    {
        return $this->belongsTo(Overview::class);
    }
}
