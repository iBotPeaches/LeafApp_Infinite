<?php

declare(strict_types=1);

use App\Models\Gamevariant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gamevariants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('name', 32);
        });

        Schema::table('games', function (Blueprint $table) {
            $table
                ->foreignIdFor(Gamevariant::class)
                ->nullable(true)
                ->after('playlist_id')
                ->constrained()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(Gamevariant::class);
        });

        Schema::dropIfExists('gamevariants');
    }
};
