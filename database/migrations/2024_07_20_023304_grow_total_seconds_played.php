<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('overview_stats', function (Blueprint $table) {
            $table->bigInteger('total_seconds_played')->change();
        });
    }

    public function down(): void
    {
        Schema::table('overview_stats', function (Blueprint $table) {
            $table->integer('total_seconds_played')->change();
        });
    }
};
