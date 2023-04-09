<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('is_scored');
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->boolean('is_scored')->after('is_ffa');
        });
    }
};
