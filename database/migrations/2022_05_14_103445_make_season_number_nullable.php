<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE `service_records` MODIFY `season_number` TINYINT(3) unsigned null DEFAULT(1);');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `service_records` MODIFY `season_number` TINYINT(3) unsigned DEFAULT(1);');
    }
};
