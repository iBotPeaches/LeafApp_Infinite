<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medal_analytics', function (Blueprint $table) {
            $table->index(['place', 'mode', 'season_id']);
        });
    }

    public function down(): void
    {
        Schema::table('medal_analytics', function (Blueprint $table) {
            $table->dropIndex(['place', 'mode', 'season_id']);
        });
    }
};
