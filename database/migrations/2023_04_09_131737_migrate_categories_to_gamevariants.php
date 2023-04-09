<?php

declare(strict_types=1);

use App\Models\Game;
use App\Models\Gamevariant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Collection;

return new class extends Migration
{
    public function up(): void
    {
        Game::query()
            ->whereNull('gamevariant_id')
            ->with('category')
            ->chunkById(2000, function (Collection $games) {
                $games->each(function (Game $game) {
                    $gamevariant = Gamevariant::query()
                        ->where('name', $game->category->name)
                        ->first();

                    if ($gamevariant) {
                        $game->gamevariant()->associate($gamevariant);
                        $game->saveOrFail();
                    }
                });
            });
    }

    public function down(): void
    {
        // Not possible to revert.
    }
};
