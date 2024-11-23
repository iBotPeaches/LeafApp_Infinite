<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Overview;
use App\Models\OverviewMap;
use Tests\TestCase;

class OverviewMapTest extends TestCase
{
    public function test_relation_properties(): void
    {
        $overviewMap = OverviewMap::factory()->createOne();

        $this->assertInstanceOf(Overview::class, $overviewMap->overview);
    }
}
