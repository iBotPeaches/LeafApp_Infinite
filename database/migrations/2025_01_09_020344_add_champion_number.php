<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('csrs', function (Blueprint $table) {
            $table
                ->tinyInteger('champion_rank')
                ->unsigned()
                ->after('next_csr')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('csrs', function (Blueprint $table) {
            $table->dropColumn('champion_rank');
        });
    }
};
