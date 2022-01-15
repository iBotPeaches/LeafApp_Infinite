<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropImageUrlFromCsrs extends Migration
{
    public function up(): void
    {
        Schema::table('csrs', function (Blueprint $table) {
            $table->dropColumn('tier_image_url');
        });
    }

    public function down(): void
    {
        Schema::table('csrs', function (Blueprint $table) {
            $table->string('tier_image_url');
        });
    }
}
