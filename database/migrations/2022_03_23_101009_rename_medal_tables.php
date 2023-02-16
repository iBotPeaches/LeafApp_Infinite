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
            $table->renameColumn('type', 'difficulty');
            $table->renameColumn('category', 'type');
        });
    }

    public function down(): void
    {
        Schema::table('medals', function (Blueprint $table) {
            $table->renameColumn('type', 'category');
            $table->renameColumn('difficulty', 'type');
        });
    }
};
