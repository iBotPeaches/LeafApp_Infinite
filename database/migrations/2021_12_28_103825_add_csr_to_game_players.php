<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCsrToGamePlayers extends Migration
{
    public function up(): void
    {
        Schema::table('game_players', function (Blueprint $table) {
            $table->mediumInteger('pre_csr')->unsigned()->nullable()->after('game_id');
            $table->mediumInteger('post_csr')->unsigned()->nullable()->after('pre_csr');
        });
    }

    public function down(): void
    {
        Schema::table('game_players', function (Blueprint $table) {
            $table->dropColumn('pre_csr');
            $table->dropColumn('post_csr');
        });
    }
}
