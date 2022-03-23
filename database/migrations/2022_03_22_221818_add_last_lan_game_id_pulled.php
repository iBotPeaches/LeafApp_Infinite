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
                ->foreignIdFor(Game::class, 'last_lan_game_id_pulled')
                ->after('last_custom_game_id_pulled')
                ->nullable(true)
                ->constrained('games', 'id');
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropConstrainedForeignId('last_lan_game_id_pulled');
        });
    }
};
