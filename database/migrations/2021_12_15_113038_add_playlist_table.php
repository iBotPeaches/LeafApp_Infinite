<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlaylistTable extends Migration
{
    public function up(): void
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('version');
            $table->string('name', 32);
            $table->string('thumbnail_url');
            $table->boolean('is_ranked');
            $table->tinyInteger('queue')->unsigned()->nullable(true);
            $table->tinyInteger('input')->unsigned()->nullable(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('playlists');
    }
}
