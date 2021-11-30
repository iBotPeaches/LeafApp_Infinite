<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUniqueToMetadata extends Migration
{
    public function up(): void
    {
        DB::table('categories')
            ->select('uuid', DB::raw('count(`uuid`) as occurrences'))
            ->groupBy('uuid')
            ->orderByDesc('id')
            ->having('occurrences', '>', 1)
            ->each(function (object $category) {
                DB::table('categories')
                    ->where('uuid', $category->uuid)
                    ->delete();
            });

        DB::table('maps')
            ->select('uuid', DB::raw('count(`uuid`) as occurrences'))
            ->groupBy('uuid')
            ->orderByDesc('id')
            ->having('occurrences', '>', 1)
            ->each(function (object $map) {
                DB::table('maps')
                    ->where('uuid', $map->uuid)
                    ->delete();
            });

        Schema::table('categories', function (Blueprint $table) {
            $table->unique('uuid');
        });

        Schema::table('maps', function (Blueprint $table) {
            $table->unique('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('metadata', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
        });

        Schema::table('maps', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
        });
    }
}
