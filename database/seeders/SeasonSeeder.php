<?php

namespace Database\Seeders;

use App\Models\Season;
use Illuminate\Database\Seeder;

class SeasonSeeder extends Seeder
{
    public function run(): void
    {
        Season::query()
            ->updateOrCreate([
                'key' => '1-1',
            ], [
                'identifier' => 'Season1',
                'csr_key' => 'CsrSeason1',
                'season_id' => 1,
                'season_version' => 1,
                'name' => 'Heroes of Reach - Part 1',
                'description' => '',
            ]);
    }
}
