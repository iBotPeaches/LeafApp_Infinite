<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Csr;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CsrModelTest extends TestCase
{
    #[DataProvider('rankDataProvider')]
    public function testRankAttribute(string $tier, int $subTier, int $nextCsr, string $expected): void
    {
        // Arrange
        $csr = Csr::factory()
            ->make([
                'tier' => $tier,
                'sub_tier' => $subTier,
                'next_csr' => $nextCsr,
            ]);

        // Act & Assert
        $this->assertEquals($expected, $csr->rank);
    }

    #[DataProvider('nextRankDataProvider')]
    public function testNextRankAttribute(
        string $tier,
        int $subTier,
        int $nextCsr,
        string $nextTier,
        int $nextCsrTier,
        string $expected
    ): void {
        // Arrange
        $csr = Csr::factory()
            ->create([
                'tier' => $tier,
                'sub_tier' => $subTier,
                'next_csr' => $nextCsr,
                'next_tier' => $nextTier,
                'next_sub_tier' => $nextCsrTier,
            ]);

        // Act & Assert
        $this->assertEquals($expected, $csr->next_rank);
    }

    public function testNoPercentWith0Xp(): void
    {
        // Arrange
        $csr = Csr::factory()
            ->create([
                'next_csr' => 1500,
                'tier_start_csr' => 1500,
            ]);

        // Act & Assert
        $this->assertEquals(0.0, $csr->next_rank_percent);
    }

    public static function nextRankDataProvider(): array
    {
        return [
            'diamond' => [
                'tier' => 'Diamond',
                'subTier' => 0, // 0 index in the API
                'nextCsr' => 1250,
                'nextTier' => 'Diamond',
                'nextCsrTier' => 1,
                'expected' => 'Diamond 2',
            ],
            'diamond 6' => [
                'tier' => 'Diamond',
                'subTier' => 5,
                'nextCsr' => 1500,
                'nextTier' => 'Onyx',
                'nextCsrTier' => 0,
                'expected' => 'Onyx',
            ],
            'onyx' => [
                'tier' => 'Onyx',
                'subTier' => 0,
                'nextCsr' => 1500,
                'nextTier' => 'Onyx',
                'nextCsrTier' => 0,
                'expected' => 'Onyx',
            ],
            'unranked' => [
                'tier' => 'Unranked',
                'subTier' => 0,
                'nextCsr' => 0,
                'nextTier' => '',
                'nextCsrTier' => 0,
                'expected' => '',
            ],
        ];
    }

    public static function rankDataProvider(): array
    {
        return [
            'diamond' => [
                'tier' => 'Diamond',
                'subTier' => 0, // 0 index in the API
                'nextCsr' => 1250,
                'expected' => 'Diamond 1',
            ],
            'onyx' => [
                'tier' => 'Onyx',
                'subTier' => 0,
                'nextCsr' => 1500,
                'expected' => 'Onyx',
            ],
            'unranked' => [
                'tier' => 'Unranked',
                'subTier' => 0,
                'nextCsr' => 0,
                'expected' => 'Unranked',
            ],
        ];
    }
}
