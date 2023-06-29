<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('analytics', function (Blueprint $table) {
            $table
                ->smallInteger('place', false, true)
                ->nullable()
                ->after('key');
        });
    }

    public function down(): void
    {
        Schema::table('analytics', function (Blueprint $table) {
            $table->dropColumn('place');
        });
    }
};
