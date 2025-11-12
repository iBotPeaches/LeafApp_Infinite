<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_players', function (Blueprint $table) {
            $table->decimal('kd', 10, 2)->change();
            $table->decimal('kda', 10, 2)->change();
        });

        Schema::table('service_records', function (Blueprint $table) {
            $table->decimal('kd', 10, 2)->change();
            $table->decimal('kda', 10, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('game_players', function (Blueprint $table) {
            $table->double('kd')->change();
            $table->double('kda')->change();
        });

        Schema::table('service_records', function (Blueprint $table) {
            $table->double('kd')->change();
            $table->double('kda')->change();
        });
    }
};
