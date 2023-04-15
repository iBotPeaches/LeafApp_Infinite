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
        Schema::table('csrs', function (Blueprint $table) {
            $table->char('season_key', 6)->after('season')->nullable();
        });

        DB::table('csrs')
            ->where('season', 1)
            ->update([
                'season_key' => '1-1',
            ]);

        DB::table('csrs')
            ->where('season', 2)
            ->update([
                'season_key' => '2-1',
            ]);

        DB::table('csrs')
            ->where('season', 3)
            ->orWhereNull('season')
            ->update([
                'season_key' => '3-1',
            ]);

        Schema::table('csrs', function (Blueprint $table) {
            $table->unique(['player_id', 'playlist_id', 'queue', 'input', 'mode', 'season_key']);
            $table->dropUnique(['player_id', 'playlist_id', 'queue', 'input', 'season', 'mode']);
        });
    }

    public function down(): void
    {
        throw new BadMethodCallException('Reversing this migration is not supported.');
    }
};
