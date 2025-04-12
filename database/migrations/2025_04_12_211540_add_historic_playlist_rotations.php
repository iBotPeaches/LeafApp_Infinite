<?php

declare(strict_types=1);

use App\Models\Playlist;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('playlist_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Playlist::class);
            $table->string('rotation_hash')->nullable();
            $table->json('rotations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('playlist_changes');
    }
};
