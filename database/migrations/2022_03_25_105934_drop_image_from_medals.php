<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medals', function (Blueprint $table) {
            $table->dropColumn('thumbnail_url');
        });
    }

    public function down(): void
    {
        throw new BadMethodCallException('Reversing this migration is not supported.');
    }
};
