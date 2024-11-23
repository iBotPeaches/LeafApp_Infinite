<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Overview;
use App\Models\OverviewGametype;
use App\Models\OverviewMap;
use App\Models\OverviewStat;
use Tests\TestCase;

class OverviewStatTest extends TestCase
{
    public function test_relation_properties(): void
    {
        $overviewStat = OverviewStat::factory()->createOne([
            'overview_gametype_id' => OverviewGametype::factory(),
            'overview_map_id' => OverviewMap::factory(),
        ]);

        $this->assertInstanceOf(Overview::class, $overviewStat->overview);
        $this->assertInstanceOf(OverviewGametype::class, $overviewStat->gametype);
        $this->assertInstanceOf(OverviewMap::class, $overviewStat->map);
    }
}
