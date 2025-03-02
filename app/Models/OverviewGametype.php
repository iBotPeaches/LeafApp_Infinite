<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BaseGametype;
use Database\Factories\OverviewGametypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $overview_id
 * @property BaseGametype $gametype
 * @property string $name
 * @property array $gamevariant_ids
 * @property-read Overview $overview
 *
 * @method static OverviewGametypeFactory factory(...$parameters)
 */
class OverviewGametype extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $casts = [
        'gametype' => BaseGametype::class,
        'gamevariant_ids' => 'array',
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
