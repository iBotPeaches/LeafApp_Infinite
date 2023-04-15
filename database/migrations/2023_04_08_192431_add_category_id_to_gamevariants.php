<?php

declare(strict_types=1);

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gamevariants', function (Blueprint $table) {
            $table->foreignIdFor(Category::class)->nullable()->after('id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::table('gamevariants', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(Category::class);
        });
    }
};
