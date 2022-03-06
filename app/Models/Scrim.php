<?php
declare(strict_types = 1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $user_id
 * @property boolean $is_complete
 * @property string $status_message
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 * @property-read Game[]|Collection<int,Game> $games
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
