<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->mediumInteger('rank_id', false, true)
                ->nullable()
                ->after('id');
            $table->mediumInteger('next_rank_id', false, true)
                ->nullable()
                ->after('rank_id');

            $table->foreign('rank_id')
                ->references('id')
                ->on('ranks')
                ->cascadeOnDelete();

            $table->foreign('next_rank_id')
                ->references('id')
                ->on('ranks')
                ->cascadeOnDelete();

            $table->integer('xp', false, true)
                ->nullable()
                ->after('gamertag');
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropForeign(['rank_id']);
            $table->dropForeign(['next_rank_id']);
            $table->dropColumn('rank_id');
            $table->dropColumn('next_rank_id');
            $table->dropColumn('xp');
        });
    }
};
