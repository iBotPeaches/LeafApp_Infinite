<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('version');
        });

        Schema::table('maps', function (Blueprint $table) {
            $table->dropColumn('version');
        });

        Schema::table('playlists', function (Blueprint $table) {
            $table->dropColumn('version');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->uuid('version');
        });

        Schema::table('maps', function (Blueprint $table) {
            $table->uuid('version');
        });

        Schema::table('playlists', function (Blueprint $table) {
            $table->uuid('version');
        });
    }
};
