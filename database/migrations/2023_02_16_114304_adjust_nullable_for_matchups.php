<?php
declare(strict_types=1);

use App\Models\Championship;
use App\Models\Matchup;
use App\Models\MatchupTeam;
use App\Models\Player;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matchups', function (Blueprint $table) {
            $table->dateTime('started_at')->nullable()->change();
            $table->dateTime('ended_at')->nullable()->change();
        });

        DB::statement('ALTER TABLE `matchup_teams` MODIFY `outcome` TINYINT(3) unsigned null;');
    }

    public function down(): void
    {
        //
    }
};
