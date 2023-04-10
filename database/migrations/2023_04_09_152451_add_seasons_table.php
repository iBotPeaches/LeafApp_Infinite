<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->string('key', 12)->unique();
            $table->string('identifier', 24);
            $table->string('csr_key', 24);
            $table->tinyInteger('season_id');
            $table->tinyInteger('season_version');
            $table->string('name', 64);
            $table->string('description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};
