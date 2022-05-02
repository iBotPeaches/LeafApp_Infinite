<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->tinyInteger('season_number')
                ->after('duration_seconds')
                ->nullable();

            $table->tinyInteger('season_version')
                ->after('season_number')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('season_number');
            $table->dropColumn('season_version');
        });
    }
};
