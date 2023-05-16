<?php

declare(strict_types=1);

use App\Models\Level;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('levels')->insert([
            'uuid' => Level::UNKNOWN_LEVEL_UUID,
            'name' => 'Unknown',
            'thumbnail_url' => 'unknown.jpg',
        ]);
    }

    public function down(): void
    {
        DB::table('levels')
            ->where('uuid', Level::UNKNOWN_LEVEL_UUID)
            ->delete();
    }
};
