<?php
declare(strict_types = 1);

namespace Tests\Unit\Enums;

use App\Enums\FaceItStatus;
use App\Enums\MedalType;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class FaceItStatusTest extends TestCase
{
    #[DataProvider('faceItStatusDataProvider')]
    public function testCoerce(int $status, string $word): void
    {
        $this->assertEquals($status, FaceItStatus::coerce($word)?->value);
    }

    public static function faceItStatusDataProvider(): array
    {
        return [
            [
                'status' => FaceItStatus::MANUAL_RESULT,
                'word' => 'manual_result',
            ],
            [
                'status' => FaceItStatus::SCHEDULING,
                'word' => 'scheduling',
            ],
            [
                'status' => FaceItStatus::SCHEDULED,
                'word' => 'scheduled',
            ],
            [
                'status' => FaceItStatus::PAUSED,
                'word' => 'paused',
            ],
            [
                'status' => FaceItStatus::JOIN,
                'word' => 'join',
            ],
            [
                'status' => FaceItStatus::CREATED,
                'word' => 'created',
            ],
            [
                'status' => FaceItStatus::ADJUSTMENT,
                'word' => 'adjustment',
            ]
        ];
    }
}
