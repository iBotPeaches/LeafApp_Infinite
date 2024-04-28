<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('overviews', function (Blueprint $table) {
            $table->boolean('is_manual')->default(false)->after('thumbnail_url');
        });
    }

    public function down(): void
    {
        Schema::table('overviews', function (Blueprint $table) {
            $table->dropColumn('is_manual');
        });
    }
};
