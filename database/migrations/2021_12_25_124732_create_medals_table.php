<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedalsTable extends Migration
{
    public function up(): void
    {
        Schema::create('medals', function (Blueprint $table) {
            $table->integer('id', false, true)->primary();
            $table->string('name', 32);
            $table->string('description', 255);
            $table->unsignedTinyInteger('category');
            $table->unsignedTinyInteger('type');
            $table->string('thumbnail_url');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medals');
    }
}

