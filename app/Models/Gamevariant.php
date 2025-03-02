<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\HasDotApi;
use Database\Factories\GamevariantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int|null $category_id
 * @property string $uuid
 * @property string $name
 * @property-read Category|null $category
 *
 * @method static GamevariantFactory factory(...$parameters)
 */
class Gamevariant extends Model implements HasDotApi
{
    use HasFactory;

    public $guarded = [
        'id',
        'category_id',
    ];

    public $timestamps = false;

    public static function fromDotApi(array $payload): ?self
    {
        $gamevariantId = (string) Arr::get($payload, 'id');

        $category = Category::fromMetadata($payload);

        /** @var Gamevariant $gamevariant */
        $gamevariant = self::query()
            ->where('uuid', $gamevariantId)
            ->firstOrNew([
                'uuid' => $gamevariantId,
            ]);

        $gamevariant->name = Str::after(Arr::get($payload, 'name'), ':');
        $gamevariant->category()->associate($category);

        if ($gamevariant->isDirty()) {
            $gamevariant->save();
        }

        return $gamevariant;
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
