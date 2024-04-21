<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Overview;
use Tests\TestCase;
use Tests\Traits\HasProxiedImageUrls;

class OverviewTest extends TestCase
{
    use HasProxiedImageUrls;

    public function testImageProperty(): void
    {
        $overview = Overview::factory()->createOne([
            'slug' => 'absolution',
        ]);

        $this->assertStringContainsString('absolution', $overview->image);
    }
}
