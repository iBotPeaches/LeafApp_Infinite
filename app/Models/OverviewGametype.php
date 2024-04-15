<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BaseGametype;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $overview_id
 * @property BaseGametype $gametype
 * @property string $name
 * @property array $gamevariant_ids
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
}
