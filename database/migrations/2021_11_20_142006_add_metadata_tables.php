<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetadataTables extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('version');
            $table->string('name', 32);
            $table->string('thumbnail_url');
        });

        Schema::create('maps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('version');
            $table->string('name', 32);
            $table->string('thumbnail_url');
        });
    }

    public function down(): void
    {
        //
    }
}
