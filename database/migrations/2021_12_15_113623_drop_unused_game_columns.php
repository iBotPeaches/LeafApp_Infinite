<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUnusedGameColumns extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('queue');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('input');
        });
    }

    public function down(): void
    {
        throw new BadMethodCallException('Reversing this migration is not supported.');
    }
}
