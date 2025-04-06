<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Input;
use App\Enums\Queue;
use App\Models\Contracts\HasDotApi;
use App\Models\Traits\HasPlaylist;
use Database\Factories\PlaylistFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $description
 * @property bool $is_ranked
 * @property bool $is_active
 * @property Queue|null $queue
 * @property Input|null $input
 * @property array|null $rotations
 * @property string $image_url
 * @property-read string $image
 * @property-read ?PlaylistStat $stat
 * @property-read Collection<int, PlaylistAnalytic> $analytics
 *
 * @method static PlaylistFactory factory(...$parameters)
 */
class Playlist extends Model implements HasDotApi
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

    public function getImageAttribute(): string
    {
        $filename = Str::slug($this->name).'.jpg';

        if (File::exists(public_path('images/playlists/'.$filename))) {
            return asset('images/playlists/'.$filename);
        }

        return $this->image_url;
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public static function fromPlaylistId(string $playlistId): ?self
    {
        return self::query()
            ->where('uuid', $playlistId)
            ->first();
    }

    public static function fromDotApi(array $payload): ?self
    {
        $playlistId = Arr::get($payload, 'id');

        /** @var Playlist $playlist */
        $playlist = self::query()
            ->where('uuid', $playlistId)
            ->firstOrNew([
                'uuid' => $playlistId,
            ]);

        $playlist->name = Arr::get($payload, 'name');
        $playlist->description = Arr::get($payload, 'description');
        $playlist->is_ranked = Arr::get($payload, 'attributes.ranked');
        $playlist->is_active = Arr::get($payload, 'attributes.active');
        $playlist->queue = Arr::get($payload, 'properties.queue');
        $playlist->input = Arr::get($payload, 'properties.input');
        $playlist->rotations = Arr::get($payload, 'rotation', []);
        $playlist->image_url = Arr::get($payload, 'image_urls.thumbnail');

        if ($playlist->isDirty()) {
            $playlist->save();
        }

        return $playlist;
    }

    public function stat(): HasOne
    {
        return $this->hasOne(PlaylistStat::class);
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(PlaylistAnalytic::class);
    }
}
