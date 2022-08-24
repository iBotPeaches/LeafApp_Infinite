<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('csrs', function (Blueprint $table) {
            $table->dropForeign(['player_id']);
            $table->dropUnique(['player_id', 'queue', 'input', 'season', 'mode']);
        });

        Schema::table('csrs', function (Blueprint $table) {
            $table->foreign('player_id')
                ->on('players')
                ->references('id')
                ->onDelete('CASCADE');

            $table->unique(['player_id', 'playlist_id', 'queue', 'input', 'season', 'mode']);
        });
    }

    public function down(): void
    {
        throw new BadMethodCallException('Reversing this migration is not supported.');
    }
};
