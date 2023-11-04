<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\User;
use Tests\TestCase;

class RanksPageTest extends TestCase
{
    public function testLoadingRanksAsGuest(): void
    {
        // Arrange & Act
        $response = $this->get('/ranks');

        // Assert
        $response->assertSuccessful();
        $response->assertSeeLivewire('ranks-page');

    }

    public function testLoadingRanksAsUser(): void
    {
        // Arrange
        $user = User::factory()->createOne();

        // Act
        $response = $this
            ->actingAs($user)
            ->get('/ranks');

        // Assert
        $response->assertSuccessful();
        $response->assertSeeLivewire('ranks-page');
    }
}
