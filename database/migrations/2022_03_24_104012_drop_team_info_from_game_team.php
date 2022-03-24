<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_teams', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('emblem_url');
        });
    }

    public function down(): void
    {
        throw new Exception('Reversing this migration is not supported.');
    }
};
