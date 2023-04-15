<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE `games` CHANGE `category_id` `category_id` BIGINT(20) UNSIGNED NULL;');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `games` CHANGE `category_id` `category_id` BIGINT(20) UNSIGNED;');
    }
};