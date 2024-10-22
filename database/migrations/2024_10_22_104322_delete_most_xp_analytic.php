<?php

declare(strict_types=1);

use App\Enums\AnalyticKey;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('analytics')
            ->where('key', AnalyticKey::MOST_XP->value)
            ->delete();
    }

    public function down(): void
    {
        // This is intentionally left blank
    }
};
