<?php
declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Csr;
use Tests\TestCase;

class CsrModelTest extends TestCase
{
    /** @dataProvider rankDataProvider */
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

    /** @dataProvider nextRankDataProvider */
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

    public function nextRankDataProvider(): array
    {
        return [
            'diamond' => [
                'tier' => 'Diamond',
                'sub_tier' => 0, // 0 index in the API
                'next_csr' => 1250,
                'next_tier' => 'Diamond',
                'next_sub_tier' => 1,
                'expected' => 'Diamond 2'
            ],
            'onyx' => [
                'tier' => 'Onyx',
                'sub_tier' => 0,
                'next_csr' => 1500,
                'next_tier' => 'Onyx',
                'next_sub_tier' => 0,
                'expected' => 'Onyx'
            ],
            'unrated' => [
                'tier' => 'Unrated',
                'sub_tier' => 0,
                'next_csr' => 0,
                'next_tier' => '',
                'next_sub_tier' => 0,
                'expected' => ''
            ]
        ];
    }

    public function rankDataProvider(): array
    {
        return [
            'diamond' => [
                'tier' => 'Diamond',
                'sub_tier' => 0, // 0 index in the API
                'next_csr' => 1250,
                'expected' => 'Diamond 1'
            ],
            'onyx' => [
                'tier' => 'Onyx',
                'sub_tier' => 0,
                'next_csr' => 1500,
                'expected' => 'Onyx'
            ],
            'unrated' => [
                'tier' => 'Unrated',
                'sub_tier' => 0,
                'next_csr' => 0,
                'expected' => 'Unrated'
            ]
        ];
    }
}
