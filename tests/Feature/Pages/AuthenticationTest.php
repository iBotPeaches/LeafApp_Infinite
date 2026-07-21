<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function testAuthenticationRedirect(): void
    {
        // Arrange

        // Act
        $response = $this->get('/auth/google/redirect');

        // Assert
        $response->assertRedirect();
    }

    public function testAuthenticationCallback(): void
    {
        // Arrange
        $abstractUser = Mockery::mock(\Laravel\Socialite\Two\User::class);
        $abstractUser
            ->shouldReceive('getId')
            ->andReturn(rand());

        Socialite::shouldReceive('driver->user')->andReturn($abstractUser);

        // Act
        $response = $this->get('/auth/google/callback');

        // Assert
        $response->assertRedirect();
    }

    public function testAuthenticationCallbackWithProfileLinked(): void
    {
        // Arrange
        $googleId = rand();
        $abstractUser = Mockery::mock(\Laravel\Socialite\Two\User::class);
        $abstractUser
            ->shouldReceive('getId')
            ->andReturn($googleId);

        Socialite::shouldReceive('driver->user')->andReturn($abstractUser);

        User::factory()->createOne([
            'google_id' => $googleId,
        ]);

        // Act
        $response = $this->get('/auth/google/callback');

        // Assert
        $response->assertRedirect();
    }

    public function testAuthenticationLogout(): void
    {
        // Arrange
        $user = User::factory()->createOne();

        $this->actingAs($user);

        // Act
        $response = $this->post('/auth/logout');

        // Assert
        $response->assertRedirect();
    }
}
