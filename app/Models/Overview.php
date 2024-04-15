<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $image
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Overview extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
    ];
}
