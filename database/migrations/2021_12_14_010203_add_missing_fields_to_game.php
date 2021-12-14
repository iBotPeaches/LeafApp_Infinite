<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToGame extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->tinyInteger('queue')->unsigned()->after('experience')->nullable(true);
            $table->tinyInteger('input')->unsigned()->after('queue')->nullable(true);
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('queue');
            $table->dropColumn('input');
        });
    }
}
