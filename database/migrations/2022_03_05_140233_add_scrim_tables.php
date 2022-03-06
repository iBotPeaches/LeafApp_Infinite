<?php
declare(strict_types=1);

use App\Models\Game;
use App\Models\Scrim;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scrims', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->boolean('is_complete')->default(false);
            $table->text('status_message')->nullable(true);
            $table->timestamps();
        });

        Schema::create('game_scrim', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Scrim::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Game::class)->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_scrim');
        Schema::dropIfExists('scrims');
    }
};
