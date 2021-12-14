<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWasPulledBoolToGames extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->boolean('was_pulled')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('was_pulled');
        });
    }
}
