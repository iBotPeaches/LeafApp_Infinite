<?php

declare(strict_types=1);

namespace Tests\Feature\Forms\RanksPage;

use App\Livewire\RanksPage;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class LoggedUserRankPageTest extends TestCase
{
    public function test_valid_component_as_user(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $this->actingAs($user);

        // Act & Assert
        Livewire::test(RanksPage::class)
            ->assertSeeText($user->player?->gamertag)
            ->assertSeeText('Hero');
    }

    public function test_valid_component_as_guest(): void
    {
        // Arrange

        // Act & Assert
        Livewire::test(RanksPage::class)
            ->assertSeeText('Sign in with Google');
    }
}
