<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
