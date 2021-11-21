<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $uuid
 * @property string $version
 * @property string $name
 * @property string $thumbnail_url
 */
class Map extends Model
{
    use HasFactory;

    public $timestamps = false;
}
