<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPrivateToPlayers extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table
                ->boolean('is_private')
                ->after('service_tag')
                ->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('is_private');
        });
    }
}
