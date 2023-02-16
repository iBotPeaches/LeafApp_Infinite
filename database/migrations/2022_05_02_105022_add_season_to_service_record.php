<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_records', function (Blueprint $table) {
            $table
                ->tinyInteger('season_number', false, true)
                ->after('mode')
                ->default(1);

            $table->unique(['player_id', 'mode', 'season_number']);
            $table->dropUnique(['player_id', 'mode']);
        });
    }

    public function down(): void
    {
        Schema::table('service_records', function (Blueprint $table) {
            $table->dropUnique(['player_id', 'mode', 'season_number']);
            $table->unique(['player_id', 'mode']);
            $table->dropColumn('season_number');
        });
    }
};
