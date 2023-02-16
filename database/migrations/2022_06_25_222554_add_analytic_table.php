<?php

declare(strict_types=1);

use App\Models\Game;
use App\Models\Player;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->string('key', 32);
            $table->foreignIdFor(Game::class)->nullable(true)->constrained()->nullOnDelete();
            $table->foreignIdFor(Player::class)->constrained();
            $table->float('value', 14);
            $table->timestamps();

            $table->index(['key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics');
    }
};
