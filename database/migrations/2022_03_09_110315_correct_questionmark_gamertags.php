<?php

declare(strict_types=1);

use App\Models\Game;
use App\Models\Player;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        /** @var Player $gamer */
        $gamer = Player::query()
            ->where('gamertag', '???')
            ->first();

        if ($gamer) {
            $gamer->games->each(function (Game $game) {
                $game->was_pulled = false;
                $game->saveOrFail();
            });

            $gamer->deleteOrFail();
        }
    }

    public function down(): void
    {
        //
    }
};
