<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixUnsignedNatureOfScore extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `game_players` CHANGE `score` `score` MEDIUMINT(8) NOT NULL;');
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `game_players` CHANGE `score` `score` MEDIUMINT(8) UNSIGNED NOT NULL;');
        }
    }
}
