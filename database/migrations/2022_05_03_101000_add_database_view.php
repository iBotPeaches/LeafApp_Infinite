<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            <<<SQL
                CREATE VIEW `merged_service_records` AS
                    SELECT
                           player_id,
                           mode,
                           AVG(kd) AS kd,
                           AVG(kda) AS kda,
                           CAST(SUM(total_score) AS UNSIGNED INTEGER) AS total_score,
                           SUM(total_matches) AS total_matches,
                           SUM(matches_won) AS matches_won,
                           SUM(matches_lost) AS matches_lost,
                           SUM(matches_tied) AS matches_tied,
                           SUM(matches_left) AS matches_left,
                           SUM(total_seconds_played) AS total_seconds_played,
                           SUM(kills) AS kills,
                           SUM(deaths) AS deaths,
                           SUM(assists) AS assists,
                           SUM(betrayals) AS betrayals,
                           SUM(suicides) AS suicides,
                           SUM(medal_count) AS medal_count,
                           SUM(damage_taken) AS damage_taken,
                           SUM(damage_dealt) AS damage_dealt,
                           SUM(shots_fired) AS shots_fired,
                           SUM(shots_landed) AS shots_landed,
                           SUM(shots_missed) AS shots_missed,
                           AVG(accuracy) AS accuracy,
                           SUM(kills_melee) AS kills_melee,
                           SUM(kills_grenade) AS kills_grenade,
                           SUM(kills_headshot) AS kills_headshot,
                           SUM(kills_power) AS kills_power,
                           GROUP_CONCAT(medals SEPARATOR '|') AS medals
                    FROM service_records
                    GROUP BY player_id, mode;
            SQL
        );
    }

    public function down(): void
    {
        DB::statement(
            <<<SQL
                DROP VIEW IF EXISTS `merged_service_records`;
            SQL
        );
    }
};
