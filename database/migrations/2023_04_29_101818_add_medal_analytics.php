<?php

declare(strict_types=1);

use App\Models\Player;
use App\Models\Season;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medal_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Player::class)->constrained();
            $table->foreignIdFor(Season::class)->nullable()->constrained();
            $table->integer('medal_id', false, true);
            $table->mediumInteger('value', false, true);
            $table->smallInteger('place', false, true);
            $table->bigInteger('total_seconds_played');

            $table
                ->foreign('medal_id')
                ->references('id')
                ->on('medals')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medal_analytics');
    }
};
