<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $overview_id
 * @property int $map_id
 * @property string $released_at
 */
class OverviewMap extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
    ];

    public $timestamps = false;
}
