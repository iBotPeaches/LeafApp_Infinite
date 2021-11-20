<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $gamertag
 * @property string $service_tag
 * @property string $emblem_url
 * @property string $backdrop_url
 */
class Player extends Model
{
    use HasFactory;
}
