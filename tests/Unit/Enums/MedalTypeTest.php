<?php
declare(strict_types = 1);

namespace Tests\Unit\Enums;

use App\Enums\MedalType;
use Tests\TestCase;

class MedalTypeTest extends TestCase
{
    public function testCoerceWithSpree(): void
    {
        $this->assertEquals(MedalType::KILLING_SPREE(), MedalType::coerce('spree'));
    }
}
