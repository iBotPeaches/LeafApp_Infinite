<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_players', function (Blueprint $table) {
            $table->mediumInteger('shots_fired')->unsigned()->change();
            $table->mediumInteger('shots_landed')->unsigned()->change();
            $table->mediumInteger('shots_missed')->unsigned()->change();
        });
    }

    public function down(): void
    {
        Schema::table('game_players', function (Blueprint $table) {
            $table->smallInteger('shots_fired')->unsigned()->change();
            $table->smallInteger('shots_landed')->unsigned()->change();
            $table->smallInteger('shots_missed')->unsigned()->change();
        });
    }
};
