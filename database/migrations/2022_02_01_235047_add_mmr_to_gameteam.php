<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMmrToGameteam extends Migration
{
    public function up(): void
    {
        Schema::table('game_teams', function (Blueprint $table) {
            $table->decimal('mmr', 7, 3)->nullable(true)->after('score');
        });
    }

    public function down(): void
    {
        Schema::table('game_teams', function (Blueprint $table) {
            $table->dropColumn('mmr');
        });
    }
}
