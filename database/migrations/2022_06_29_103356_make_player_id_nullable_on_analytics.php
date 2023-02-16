<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE `analytics` MODIFY `player_id` bigint(20) unsigned null default null;');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `analytics` MODIFY `player_id` bigint(20) unsigned;');
    }
};
