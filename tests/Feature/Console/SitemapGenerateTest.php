<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\Matchup;
use App\Models\Medal;
use App\Models\Player;
use App\Models\Scrim;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Tests\TestCase;

class SitemapGenerateTest extends TestCase
{
    use WithFaker;

    public function testSitemapGeneration(): void
    {
        // Arrange
        Matchup::factory()->createOne();
        Scrim::factory()->createOne();
        Medal::factory()->createOne();
        Player::factory()->createOne();

        // Act & Assert
        $this
            ->artisan('sitemap:generate')
            ->assertExitCode(CommandAlias::SUCCESS);
    }
}
