<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropIsRankedFromGames extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('is_ranked');
        });
    }

    public function down(): void
    {
        throw new BadMethodCallException('Reversing this migration is not supported.');
    }
}
