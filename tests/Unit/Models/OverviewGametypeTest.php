<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Overview;
use App\Models\OverviewGametype;
use Tests\TestCase;

class OverviewGametypeTest extends TestCase
{
    public function testRelationProperties(): void
    {
        $overviewGametype = OverviewGametype::factory()->createOne();

        $this->assertInstanceOf(Overview::class, $overviewGametype->overview);
    }
}
