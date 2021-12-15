<?php

use App\Models\Category;
use App\Models\Playlist;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlaylistIdToGames extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->foreignIdFor(Playlist::class)
                ->nullable(true)
                ->after('map_id')
                ->constrained();
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropConstrainedForeignId('playlist_id');
        });
    }
}
