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
            $table
                ->unsignedSmallInteger('expected_kills')
                ->nullable(true)
                ->after('assists_callout');

            $table
                ->unsignedSmallInteger('expected_deaths')
                ->nullable(true)
                ->after('expected_kills');
        });
    }

    public function down(): void
    {
        Schema::table('game_players', function (Blueprint $table) {
            $table->dropColumn('expected_kills');
            $table->dropColumn('expected_deaths');
        });
    }
};
