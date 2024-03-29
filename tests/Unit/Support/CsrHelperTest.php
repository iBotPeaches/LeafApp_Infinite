<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\Csr\CsrHelper;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CsrHelperTest extends TestCase
{
    #[DataProvider('csrDataProvider')]
    public function testCsrCalculationToRank(int $csr, string $expected)
    {
        $this->assertEquals(
            $expected,
            CsrHelper::getCsrFromValue($csr, null)->title,
            $csr.' csr is not: '.$expected
        );
    }

    #[DataProvider('csrDataProvider')]
    public function testCsrCalculationToAsset(int $csr, string $expected)
    {
        $this->assertStringEndsWith(
            Str::slug($expected).'.png',
            CsrHelper::getCsrFromValue($csr, null)->url(),
            $csr.' url() is not ending with proper string.'
        );
    }

    #[DataProvider('unrankedCsrDataProvider')]
    public function testUnrankedCsrCalculationToRank(?int $matchesRemaining, string $expected)
    {
        $this->assertEquals(
            $expected,
            CsrHelper::getCsrFromValue(0, $matchesRemaining)->title,
            $matchesRemaining.' matches remaining is not: '.$expected
        );
    }

    #[DataProvider('unrankedCsrDataProvider')]
    public function testUnrankedCsrCalculationToAsset(?int $matchesRemaining, string $expected)
    {
        $matchesCompleted = $matchesRemaining === null ? 0 : (10 - $matchesRemaining);

        $this->assertStringEndsWith(
            Str::slug($expected.'-'.$matchesCompleted).'.png',
            CsrHelper::getCsrFromValue(0, $matchesRemaining)->url(),
            $matchesRemaining.' url() is not ending with proper string: '.$expected
        );
    }

    public static function unrankedCsrDataProvider(): array
    {
        return [
            'unranked 0' => [
                'matchesRemaining' => 10,
                'expected' => 'Unranked',
            ],
            'unranked null' => [
                'matchesRemaining' => null,
                'expected' => 'Unranked',
            ],
            'unranked-1' => [
                'matchesRemaining' => 9,
                'expected' => 'Unranked',
            ],
            'unranked-9' => [
                'matchesRemaining' => 1,
                'expected' => 'Unranked',
            ],
        ];
    }

    public static function csrDataProvider(): array
    {
        return [
            'bronze 1' => [
                'csr' => 1,
                'expected' => 'Bronze 1',
            ],
            'bronze 4' => [
                'csr' => 150,
                'expected' => 'Bronze 4',
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
                'expected' => 'Gold 1',
            ],
            'gold 6' => [
                'csr' => 899,
                'expected' => 'Gold 6',
            ],
            'platinum 1' => [
                'csr' => 901,
                'expected' => 'Platinum 1',
            ],
            'platinum 6' => [
                'csr' => 1151,
                'expected' => 'Platinum 6',
            ],
            'diamond 1' => [
                'csr' => 1200,
                'expected' => 'Diamond 1',
            ],
            'diamond 6' => [
                'csr' => 1499,
                'expected' => 'Diamond 6',
            ],
            'onyx small' => [
                'csr' => 1500,
                'expected' => 'Onyx',
            ],
            'onyx large' => [
                'csr' => 2500,
                'expected' => 'Onyx',
            ],
        ];
    }
}
