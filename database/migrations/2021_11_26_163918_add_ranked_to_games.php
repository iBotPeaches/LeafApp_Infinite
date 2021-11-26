<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRankedToGames extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table
                ->boolean('is_ranked')
                ->after('map_id')
                ->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('is_ranked');
        });
    }
}
