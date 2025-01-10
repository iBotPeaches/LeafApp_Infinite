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
    public function test_csr_calculation_to_rank(int $csr, string $expected): void
    {
        $this->assertEquals(
            $expected,
            CsrHelper::getCsrFromValue($csr, null, null)->title,
            $csr.' csr is not: '.$expected
        );
    }

    #[DataProvider('csrDataProvider')]
    public function test_csr_calculation_to_asset(int $csr, string $expected): void
    {
        $this->assertStringEndsWith(
            Str::slug($expected).'.png',
            CsrHelper::getCsrFromValue($csr, null, null)->url(),
            $csr.' url() is not ending with proper string.'
        );
    }

    #[DataProvider('unrankedCsrDataProvider')]
    public function test_unranked_csr_calculation_to_rank(?int $matchesRemaining, string $expected): void
    {
        $this->assertEquals(
            $expected,
            CsrHelper::getCsrFromValue(0, $matchesRemaining, null)->title,
            $matchesRemaining.' matches remaining is not: '.$expected
        );
    }

    #[DataProvider('unrankedCsrDataProvider')]
    public function test_unranked_csr_calculation_to_asset(?int $matchesRemaining, string $expected): void
    {
        $matchesCompleted = $matchesRemaining === null ? 0 : (5 - $matchesRemaining);

        $this->assertStringEndsWith(
            Str::slug($expected.'-'.$matchesCompleted).'.png',
            CsrHelper::getCsrFromValue(0, $matchesRemaining, null)->url(),
            $matchesRemaining.' url() is not ending with proper string: '.$expected
        );
    }

    #[DataProvider('championDataProvider')]
    public function test_champion_calculation_to_rank(int $csr, ?int $matchesRemaining, ?int $championRank, string $expected): void
    {
        $this->assertEquals(
            $expected,
            CsrHelper::getCsrFromValue($csr, $matchesRemaining, $championRank)->title,
            $championRank.' champion rank is not: '.$expected
        );
    }

    #[DataProvider('championDataProvider')]
    public function test_champion_calculation_to_asset(int $csr, ?int $matchesRemaining, ?int $championRank, string $expected): void
    {
        $this->assertStringEndsWith(
            Str::slug($expected).'.png',
            CsrHelper::getCsrFromValue($csr, $matchesRemaining, $championRank)->url(),
            $championRank.' url() is not ending with proper string: '.$expected
        );
    }

    public static function championDataProvider(): array
    {
        return [
            'champion 200' => [
                'csr' => 1500,
                'matchesRemaining' => 0,
                'championRank' => 200,
                'expected' => 'Champion',
            ],
            'champion 1' => [
                'csr' => 1500,
                'matchesRemaining' => 0,
                'championRank' => 1,
                'expected' => 'Champion',
            ],
            'champion null' => [
                'csr' => 1500,
                'matchesRemaining' => 0,
                'championRank' => null,
                'expected' => 'Onyx',
            ],
        ];
    }

    public static function unrankedCsrDataProvider(): array
    {
        return [
            'unranked 0' => [
                'matchesRemaining' => 5,
                'expected' => 'Unranked',
            ],
            'unranked null' => [
                'matchesRemaining' => null,
                'expected' => 'Unranked',
            ],
            'unranked-1' => [
                'matchesRemaining' => 4,
                'expected' => 'Unranked',
            ],
            'unranked-4' => [
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
