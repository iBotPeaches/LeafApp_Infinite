<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE `matchup_teams` MODIFY `points` TINYINT(4) unsigned null;');
    }

    public function down(): void
    {
        // This is intentionally blank as reversing a null add would not be possible.
    }
};
