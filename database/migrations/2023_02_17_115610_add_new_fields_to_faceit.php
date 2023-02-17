<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('championships', function (Blueprint $table) {
            $table->tinyInteger('status', false, true)->after('region');
            $table->text('description')->after('type');
        });

        Schema::table('matchups', function (Blueprint $table) {
            $table->tinyInteger('status', false, true)->after('best_of');
        });
    }

    public function down(): void
    {
        Schema::table('championships', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('description');
        });

        Schema::table('matchups', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
