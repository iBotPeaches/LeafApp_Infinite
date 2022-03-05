<?php
declare(strict_types = 1);

namespace App\Models;

use App\Models\Pivots\ScrimGame;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 */
class Scrim extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function createScrimWithGames(User $user, array $gameIds): self
    {
        $model = new self();
        $model->user()->associate($user);
        $model->saveOrFail();

        $model->games()->sync($gameIds);

        return $model;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class);
    }
}
