<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Input;
use App\Enums\Queue;
use App\Models\Contracts\HasHaloDotApi;
use App\Models\Traits\HasPlaylist;
use Database\Factories\PlaylistFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property bool $is_ranked
 * @property bool $is_active
 * @property Queue|null $queue
 * @property Input|null $input
 * @property array|null $rotations
 *
 * @method static PlaylistFactory factory(...$parameters)
 */
class Playlist extends Model implements HasHaloDotApi
{
    use HasFactory, HasPlaylist;

    public $guarded = [
        'id',
    ];

    public $timestamps = false;

    public $casts = [
        'queue' => Queue::class,
        'input' => Input::class,
        'is_ranked' => 'bool',
        'is_active' => 'bool',
        'rotations' => 'array',
    ];

    public function getNameAttribute(string $value): string
    {
        if ($value === 'Unknown') {
            return 'Featured';
        }

        return $value;
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public static function fromPlaylistId(string $playlistId): ?self
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return self::query()
            ->where('uuid', $playlistId)
            ->first();
    }

    public static function fromHaloDotApi(array $payload): ?self
    {
        $playlistId = Arr::get($payload, 'id');

        /** @var Playlist $playlist */
        $playlist = self::query()
            ->where('uuid', $playlistId)
            ->firstOrNew([
                'uuid' => $playlistId,
            ]);

        $playlist->name = Arr::get($payload, 'name');
        $playlist->is_ranked = Arr::get($payload, 'attributes.ranked');
        $playlist->is_active = Arr::get($payload, 'attributes.active');
        $playlist->queue = Arr::get($payload, 'properties.queue');
        $playlist->input = Arr::get($payload, 'properties.input');
        $playlist->rotations = Arr::get($payload, 'rotation', []);

        if ($playlist->isDirty()) {
            $playlist->saveOrFail();
        }

        return $playlist;
    }
}
