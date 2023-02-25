<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('players')
            ->whereNotNull('last_lan_game_id_pulled')
            ->update(['last_lan_game_id_pulled' => null]);

        DB::statement('ALTER TABLE `games` MODIFY `is_lan` TINYINT(1) unsigned null;');

        DB::table('games')
            ->where('is_lan', false)
            ->update(['is_lan' => null]);
    }

    public function down(): void
    {
        // This is intentionally blank and not possible to revert.
    }
};
