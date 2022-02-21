<?php

use App\Models\Player;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompetitiveTable extends Migration
{
    public function up(): void
    {
        Schema::create('csrs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Player::class)->constrained()->cascadeOnDelete();
            $table->tinyInteger('queue')->unsigned();
            $table->tinyInteger('input')->unsigned();
            $table->tinyInteger('season')->unsigned()->nullable();

            $table->mediumInteger('csr')->unsigned()->nullable();
            $table->tinyInteger('matches_remaining')->unsigned()->default(0);

            $table->string('tier', 16);
            $table->string('tier_image_url');
            $table->tinyInteger('sub_tier')->unsigned();

            $table->string('next_tier', 16);
            $table->tinyInteger('next_sub_tier')->unsigned();
            $table->mediumInteger('next_csr')->unsigned();

            $table->string('season_tier', 16);
            $table->tinyInteger('season_sub_tier')->unsigned();

            $table->unique(['player_id', 'queue', 'input', 'season']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('csrs');
    }
}
