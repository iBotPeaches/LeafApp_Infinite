<?php
declare(strict_types=1);

use App\Models\Player;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_bans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Player::class)->constrained();
            $table->string('message');
            $table->dateTime('ends_at');
            $table->string('type', 24);
            $table->string('scope', 24);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_bans');
    }
};
