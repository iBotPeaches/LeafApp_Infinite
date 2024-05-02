<?php

declare(strict_types=1);

use App\Models\Map;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('analytics', function (Blueprint $table) {
            $table->dropColumn('map_id');
        });
    }

    public function down(): void
    {
        Schema::table('analytics', function (Blueprint $table) {
            $table->foreignIdFor(Map::class);
        });
    }
};
