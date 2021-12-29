<?php
declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\Csr\CsrHelper;
use Tests\TestCase;

class CsrHelperTest extends TestCase
{
    /** @dataProvider csrDataProvider */
    public function testCsrCalculationToRank(int $csr, string $expected)
    {
        $this->assertEquals(
            $expected,
            CsrHelper::getCsrFromValue($csr)->rank,
            $csr . ' csr is not: ' . $expected
        );
    }

    public function csrDataProvider(): array
    {
        return [
            'unranked' => [
                'csr' => 0,
                'expected' => 'Unranked'
            ],
            'bronze 1' => [
                'csr' => 1,
                'expected' => 'Bronze 1'
            ],
            'bronze 4' => [
                'csr' => 150,
                'expected' => 'Bronze 4'
            ],
            'bronze 6' => [
                'csr' => 250,
                'expected' => 'Bronze 6',
            ],
            'silver 1' => [
                'csr' => 300,
                'expected' => 'Silver 1',
            ],
            'silver 6' => [
                'csr' => 551,
                'expected' => 'Silver 6',
            ],
            'gold 1' => [
                'csr' => 602,
                'expected' => 'Gold 1'
            ],
            'gold 6' => [
                'csr' => 899,
                'expected' => 'Gold 6'
            ],
            'platinum 1' => [
                'csr' => 901,
                'expected' => 'Platinum 1'
            ],
            'platinum 6' => [
                'csr' => 1151,
                'expected' => 'Platinum 6'
            ],
            'diamond 1' => [
                'csr' => 1200,
                'expected' => 'Diamond 1'
            ],
            'diamond 6' => [
                'csr' => 1499,
                'expected' => 'Diamond 6'
            ],
            'onyx small' => [
                'csr' => 1500,
                'expected' => 'Onyx'
            ],
            'onyx large' => [
                'csr' => 2500,
                'expected' => 'Onyx'
            ]
        ];
    }
}
