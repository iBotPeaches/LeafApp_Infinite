<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPartipationColumns extends Migration
{
    public function up(): void
    {
        Schema::table('game_players', function (Blueprint $table) {
            $table->boolean('was_at_start')
                ->after('outcome')
                ->default(true);

            $table->boolean('was_at_end')
                ->after('was_at_start')
                ->default(true);

            $table->boolean('was_inprogress_join')
                ->after('was_at_end')
                ->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('game_players', function (Blueprint $table) {
            $table->dropColumn('was_at_start');
        });

        Schema::table('game_players', function (Blueprint $table) {
            $table->dropColumn('was_at_end');
        });

        Schema::table('game_players', function (Blueprint $table) {
            $table->dropColumn('was_inprogress_join');
        });
    }
}
