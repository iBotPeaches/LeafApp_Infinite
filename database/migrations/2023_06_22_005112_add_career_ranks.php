<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ranks', function (Blueprint $table) {
            $table->mediumInteger('id', false, true)->primary();
            $table->string('name', 32);
            $table->string('subtitle', 32);
            $table->tinyInteger('grade')->nullable();
            $table->tinyInteger('tier')->nullable();
            $table->string('type', 12);
            $table->integer('threshold');
            $table->integer('required');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ranks');
    }
};
