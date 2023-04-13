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
        Schema::table('service_records', function (Blueprint $table) {
            $table->char('season_key', 6)->after('season_number')->nullable();
            $table->unique(['player_id', 'mode', 'season_key']);
            $table->dropUnique(['player_id', 'mode', 'season_number']);
        });

        DB::table('service_records')
            ->where('season_number', 3)
            ->update([
                'season_key' => '3-1',
            ]);
    }

    public function down(): void
    {
        Schema::table('service_records', function (Blueprint $table) {
            $table->unique(['player_id', 'mode', 'season_number']);
            $table->dropUnique(['player_id', 'mode', 'season_key']);
            $table->dropColumn('season_key');
        });
    }
};
