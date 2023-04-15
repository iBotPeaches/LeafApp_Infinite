<?php

declare(strict_types=1);

use App\Models\Game;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('mmr');
            $table->dropForeignIdFor(Game::class, 'mmr_game_id');
            $table->dropColumn('mmr_game_id');
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->decimal('mmr', 7, 3)->nullable()->after('score');
            $table->foreignIdFor(Game::class, 'mmr_game_id');
        });
    }
};
