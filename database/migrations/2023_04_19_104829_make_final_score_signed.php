<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE `game_teams` CHANGE `final_score` `final_score` MEDIUMINT(8) NULL DEFAULT NULL;');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `game_teams` CHANGE `final_score` `final_score` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL;');
    }
};
