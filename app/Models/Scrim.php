<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\ScrimFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;

/**
 * @property int $id
 * @property int $user_id
 * @property bool $is_complete
 * @property string $status_message
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 * @property-read Collection<int, Game> $games
 *
 * @method static ScrimFactory factory(...$parameters)
 */
class Scrim extends Model implements Sitemapable
{
    use HasFactory;

    protected $guarded = [];

    public static function createScrimWithGames(User $user, array $gameIds): self
    {
        $model = new self;
        $model->user()->associate($user);
        $model->save();

        $model->games()->sync($gameIds);

        return $model;
    }

    public function toSitemapTag(): Url|string|array
    {
        $url = new Url(route('scrim', $this));
        $url->setLastModificationDate($this->updated_at);
        $url->setChangeFrequency('never');

        return $url;
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany<Game, $this>
     */
    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class);
    }
}
