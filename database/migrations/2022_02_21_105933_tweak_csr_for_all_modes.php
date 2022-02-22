<?php
declare(strict_types=1);

use App\Enums\CompetitiveMode;
use App\Models\Player;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('csrs', function (Blueprint $table) {
            $table->tinyInteger('mode')
                ->after('season')
                ->default(CompetitiveMode::CURRENT);

            $table->dropColumn(['season_tier', 'season_sub_tier']);
        });
    }

    public function down(): void
    {
        Schema::table('csrs', function (Blueprint $table) {
            $table->dropColumn('mode');

            $table->string('season_tier', 16);
            $table->tinyInteger('season_sub_tier')->unsigned();
        });
    }
};
