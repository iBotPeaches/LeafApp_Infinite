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
            $table
                ->decimal('mmr', 7, 2)
                ->nullable(true)
                ->after('last_csr_key');

            $table
                ->foreignIdFor(Game::class, 'mmr_game_id')
                ->nullable(true)
                ->after('mmr')
                ->constrained('games', 'id');
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(Game::class, 'mmr_game_id');
            $table->dropColumn('mmr');
        });
    }
};
