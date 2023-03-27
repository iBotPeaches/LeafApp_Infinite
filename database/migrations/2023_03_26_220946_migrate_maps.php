<?php

declare(strict_types=1);

use App\Models\Map;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Map::query()->cursor()->each(function (Map $map) {
            if (Str::length($map->uuid) !== 32) {
                /** @var Map|null $otherMap */
                $otherMap = Map::query()
                    ->whereNot('id', $map->id)
                    ->where('name', $map->name)
                    ->first();

                if ($otherMap) {
                    DB::table('games')
                        ->where('map_id', $map->id)
                        ->update([
                            'map_id' => $otherMap->id,
                        ]);

                    $map->deleteOrFail();
                }
            }
        });
    }

    public function down(): void
    {
        // Reversing this migration is not supported.
    }
};
