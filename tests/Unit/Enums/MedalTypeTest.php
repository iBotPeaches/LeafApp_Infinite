<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\MedalType;
use Tests\TestCase;

class MedalTypeTest extends TestCase
{
    public function test_coerce_with_spree(): void
    {
        $this->assertEquals(MedalType::KILLING_SPREE(), MedalType::coerce('spree'));
    }
}
