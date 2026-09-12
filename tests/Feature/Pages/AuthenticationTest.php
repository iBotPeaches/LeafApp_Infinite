<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use App\Models\User;
use JMac\Testing\Double;
use Laravel\Socialite\Contracts\Factory;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function test_authentication_redirect(): void
    {
        // Arrange

        // Act
        $response = $this->get('/auth/google/redirect');

        // Assert
        $response->assertRedirect();
    }

    public function test_authentication_callback(): void
    {
        // Arrange
        $this->fakeSocialiteUser(rand());

        // Act
        $response = $this->get('/auth/google/callback');

        // Assert
        $response->assertRedirect();
    }

    public function test_authentication_callback_with_profile_linked(): void
    {
        // Arrange
        $googleId = rand();
        $this->fakeSocialiteUser($googleId);

        User::factory()->createOne([
            'google_id' => $googleId,
        ]);

        // Act
        $response = $this->get('/auth/google/callback');

        // Assert
        $response->assertRedirect();
    }

    public function test_authentication_logout(): void
    {
        // Arrange
        $user = User::factory()->createOne();

        $this->actingAs($user);

        // Act
        $response = $this->post('/auth/logout');

        // Assert
        $response->assertRedirect();
    }

    private function fakeSocialiteUser(int $googleId): void
    {
        $abstractUser = Double::for(\Laravel\Socialite\Two\User::class);
        $abstractUser->allows('getId')->returns($googleId);

        $provider = Double::for(Provider::class);
        $provider->allows('user')->returns($abstractUser);

        $socialite = Double::for(Factory::class);
        $socialite->allows('driver')->returns($provider);

        Socialite::swap($socialite);
    }
}
