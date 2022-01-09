<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinalScoreToTeams extends Migration
{
    public function up(): void
    {
        Schema::table('game_teams', function (Blueprint $table) {
            $table->mediumInteger('final_score')->unsigned()->nullable(true);
        });
    }

    public function down(): void
    {
        Schema::table('game_teams', function (Blueprint $table) {
            $table->dropColumn('final_score');
        });
    }
}
