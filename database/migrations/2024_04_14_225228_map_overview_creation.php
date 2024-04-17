<?php

declare(strict_types=1);

use App\Models\Map;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('overviews', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->string('slug', 32);
            $table->string('image', 255);
            $table->timestamps();
        });

        Schema::create('overview_maps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('overview_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Map::class)->constrained();
            $table->timestamp('released_at');

            $table->unique(['overview_id', 'map_id']);
        });

        Schema::create('overview_gametypes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('overview_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('gametype');
            $table->string('name', 32);
            $table->json('gamevariant_ids');

            $table->unique(['overview_id', 'gametype']);
        });

        Schema::create('overview_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('overview_id')->constrained()->cascadeOnDelete();
            $table
                ->foreignId('overview_gametype_id')
                ->nullable()
                ->references('id')
                ->on('overview_gametypes')
                ->cascadeOnDelete();
            $table
                ->foreignId('overview_map_id')
                ->nullable()
                ->references('id')
                ->on('overview_maps')
                ->cascadeOnDelete();

            $table->integer('total_matches');
            $table->integer('total_seconds_played');
            $table->integer('total_players');
            $table->integer('total_unique_players');
            $table->integer('total_dnf');
            $table->integer('total_kills');
            $table->integer('total_deaths');
            $table->integer('total_assists');
            $table->integer('total_suicides');
            $table->integer('total_medals');

            $table->double('average_kd');
            $table->double('average_kda');
            $table->double('average_accuracy');

            $table->unique(['overview_id', 'overview_gametype_id', 'overview_map_id'], 'overview_stats_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('overview_stats');
        Schema::dropIfExists('overview_gametypes');
        Schema::dropIfExists('overview_maps');
        Schema::dropIfExists('overviews');
    }
};
