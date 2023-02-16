<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_teams', function (Blueprint $table) {
            $table
                ->decimal('winning_percent', 5, 2)
                ->after('mmr')
                ->nullable(true);
        });
    }

    public function down(): void
    {
        Schema::table('game_teams', function (Blueprint $table) {
            $table->dropColumn('winning_percent');
        });
    }
};
