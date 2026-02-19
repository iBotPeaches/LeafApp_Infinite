<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Player;
use App\Models\PlayerBan;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PlayerBanTest extends TestCase
{
    public function test_get_message_attribute_when_not_expired(): void
    {
        // Arrange
        $player = Player::factory()->make([
            'gamertag' => 'Leafy',
        ]);
        $playerBan = PlayerBan::factory()
            ->make([
                'message' => 'You have been suspended from matchmaking for unsporting conduct. Your suspension will end at the following time: 02/07/2026, 1:43 AM',
                'ends_at' => Carbon::now()->addDay(),
            ]);
        $playerBan->setRelation('player', $player);

        // Act
        $message = $playerBan->message;

        // Assert
        $this->assertEquals("Leafy has been suspended from matchmaking for unsporting conduct. Leafy's suspension will end at the following time: 02/07/2026, 1:43 AM", $message);
    }

    public function test_get_message_attribute_when_expired(): void
    {
        // Arrange
        $player = Player::factory()->make([
            'gamertag' => 'Leafy',
        ]);
        $playerBan = PlayerBan::factory()
            ->expired()
            ->make([
                'message' => 'You have been suspended from matchmaking for unsporting conduct. Your suspension will end at the following time: 02/07/2026, 1:43 AM',
                'ends_at' => Carbon::parse('2026-02-07 01:43:00'),
            ]);
        $playerBan->setRelation('player', $player);

        // Act
        $message = $playerBan->message;

        // Assert
        $this->assertEquals("Leafy had been suspended from matchmaking for unsporting conduct. Leafy's suspension ended at the following time: 02/07/2026, 1:43 AM", $message);
    }

    public function test_get_short_message_attribute(): void
    {
        // Arrange
        $player = Player::factory()->make([
            'gamertag' => 'Leafy',
        ]);
        $playerBan = PlayerBan::factory()
            ->make([
                'message' => 'You have been suspended from matchmaking for unsporting conduct. Your suspension will end at the following time: 02/07/2026, 1:43 AM',
                'ends_at' => Carbon::now()->addDay(),
            ]);
        $playerBan->setRelation('player', $player);

        // Act
        $shortMessage = $playerBan->short_message;

        // Assert
        $this->assertEquals('Leafy has been suspended from matchmaking for unsporting conduct. ', $shortMessage);
    }

    public function test_get_days_remaining_attribute(): void
    {
        // Arrange
        $endsAt = Carbon::now()->addDays(5);
        $playerBan = new PlayerBan([
            'ends_at' => $endsAt,
        ]);

        // Act
        $daysRemaining = $playerBan->days_remaining;

        // Assert
        $this->assertEquals('5', $daysRemaining);
    }

    public function test_get_is_expired_attribute(): void
    {
        // Arrange
        $expiredBan = new PlayerBan(['ends_at' => Carbon::now()->subDay()]);
        $activeBan = new PlayerBan(['ends_at' => Carbon::now()->addDay()]);

        // Act & Assert
        $this->assertTrue($expiredBan->is_expired);
        $this->assertFalse($activeBan->is_expired);
    }
}
