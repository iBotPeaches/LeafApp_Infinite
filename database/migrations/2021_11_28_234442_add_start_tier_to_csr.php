<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartTierToCsr extends Migration
{
    public function up(): void
    {
        Schema::table('csrs', function (Blueprint $table) {
            $table->mediumInteger('tier_start_csr')
                ->after('tier')
                ->unsigned()
                ->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('csrs', function (Blueprint $table) {
            $table->dropColumn('tier_start_csr');
        });
    }
}
